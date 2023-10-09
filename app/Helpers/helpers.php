<?php

use App\Helpers\ResponseMapper;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

if (!function_exists('appResponse')) {
    /**
     * @param Request $request
     * @param mixed|null $content
     * @param int $status
     * @param array $headers
     * @return ResponseMapper|JsonResponse
     */
    function appResponse(
        Request $request,
        mixed $content = null,
        int $status = 200,
        array $headers = []
    ): ResponseMapper|JsonResponse{
        $factory = new App\Helpers\ResponseMapper();

        return $factory->createResponse($request, $content, $status, $headers);
    }
}

if (!function_exists('isInternalRequest')) {
    /**
     * @param Request $request
     * @return bool
     */
    function isInternalRequest(Request $request): bool
    {
        if (!$request->hasHeader('Internal-Api-Key')) {
            return false;
        }

        $internalAPIKey = $request->header('Internal-Api-Key');

        return $internalAPIKey === config('api.internal-api-key');
    }
}
