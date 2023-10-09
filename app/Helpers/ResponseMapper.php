<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;
use function PHPUnit\Framework\isInstanceOf;

class ResponseMapper
{
    /**
     * Accept types mapper
     * Todo - need to implement to support others types eg.xml
     *
     * @var array
     */
    public const ACCEPT_TYPE_FUNCTION_MAP = [
        '*' => 'createJsonResponse', // default to json response
        'json' => 'createJsonResponse',
    ];

    /**
     * @param Request $request
     * @param mixed $data
     * @param int $code
     * @param array $headers
     * @return JsonResponse
     */
    public function createResponse(
        Request $request,
        mixed $data,
        int $code = 200,
        array $headers = []
    ): JsonResponse
    {
        $type = null;
        $acceptHeader = strtolower($request->headers->get('Accept'));
        if ($acceptHeader !== '') {
            $acceptTypeValues = $this->getAcceptHeaderValues($acceptHeader);
            foreach ($acceptTypeValues as $acceptTypeValue) {
                if ($this->isSupported($acceptTypeValue['subtype'])) {
                    $type = $acceptTypeValue['subtype'];
                    break;
                }
            }

            // Handle not found supported types
            if (is_null($type)) {
                return $this->createNotSupportResponse($request, $headers);
            }

        } else {
            // Default to wildcard if accept header is not provided
            $type = '*';
        }

        return $this->createResponseForType($request, $type, $data, $code, $headers);
    }

    /**
     * @param string $header
     * @return array
     */
    public function getAcceptHeaderValues(string $header): array
    {
        $results = [];
        $items = explode(',', $header);

        foreach ($items as $item) {
            $elems = explode(';', $item);
            $mime = current($elems);
            $types = explode('/', $mime);

            if (!isset($types[1])) {
                continue;
            }

            $acceptElement = [
                'raw'     => $mime,
                'type'    => trim($types[0]),
                'subtype' => trim($types[1])
            ];

            $acceptElement['params'] = [];
            while (next($elems)) {
                [$name, $value] = explode('=', current($elems));
                $acceptElement['params'][trim($name)] = trim($value);
            }

            $results[] = $acceptElement;
        }

        return $results;
    }

    /**
     * @param string $type
     * @return bool
     */
    public function isSupported(string $type): bool
    {
        return isset(self::ACCEPT_TYPE_FUNCTION_MAP[$type]);
    }

    /**
     * Translates Exception Error code to HTTP response code
     * @param int $code
     * @return int
     */
    public static function httpResponseCode(int $code): int
    {
        return ($code >= 100) && ($code <= 599) ? $code: 500;
    }

    /**
     * @param Request $request
     * @param string $acceptType
     * @param mixed $data
     * @param int $code
     * @param array $headers
     * @return JsonResponse
     */
    public function createResponseForType(
        Request $request,
        string $acceptType,
        mixed $data,
        int $code,
        array $headers = []
    ): JsonResponse {
        $mapFunction = self::ACCEPT_TYPE_FUNCTION_MAP[$acceptType];

        return $this->$mapFunction($request, $data, $code, $headers);
    }

    /**
     * @param Request $request
     * @param mixed $data
     * @param int $code
     * @param array $headers
     * @return JsonResponse
     */
    public function createJsonResponse(Request $request, mixed $data, int $code, array $headers = []): JsonResponse
    {
        // Return resource response
        if ($data instanceof JsonResource) {
            return $data
                ->additional($this->metaData($request))
                ->toResponse($request)
                ->setStatusCode($code)
                ->withHeaders($headers);
        }

        // Normal response
        return response()->json(
            array_merge($data, $this->metaData($request)),
            $code,
            $headers
        );
    }

    /**
     * @param Request $request
     * @param array $headers
     * @return JsonResponse
     */
    public function createNotSupportResponse(Request $request, array $headers = []): JsonResponse
    {
        return response()->json(
            array_merge([
                'message' => 'Accept header is not supported',
                'code' => Response::HTTP_NOT_ACCEPTABLE
            ], $this->metaData($request)),
            Response::HTTP_NOT_ACCEPTABLE,
            $headers
        );
    }

    /**
     * @param Request $request
     * @return array
     */
    private function metaData(Request $request): array
    {
        return [
            'meta' => [],
            'link' => $request->url()
        ];
    }
}
