<?php

namespace App\Modules\Property\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Property\Http\Resources\PropertyResource;
use App\Modules\Property\Repos\PropertyRepo;
use App\Modules\Property\Services\PropertyService;
use App\Modules\Property\Validations\PropertyValidation;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PropertyController extends Controller
{

    public function __construct(
        protected PropertyRepo $propertyRepo,
        protected PropertyService $propertyService,
        protected PropertyValidation $propertyValidation,
    ) {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $properties = $this->propertyRepo->list();
        $response = PropertyResource::collection($properties);

        return appResponse($request, $response);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function view(Request $request): JsonResponse
    {
        $property = $this->propertyRepo->findById($request->id);
        $response = new PropertyResource($property);

        return appResponse($request, $response);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function create(Request $request): JsonResponse
    {
        $data = $this->propertyValidation->validateRequest($request, 'create');

        $property = $this->propertyService->create($data);
        $response = new PropertyResource($property);

        return appResponse($request, $response);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function sell(Request $request): JsonResponse
    {
        $propertyId = $request->id;
        $soldProperty = $this->propertyRepo->sell($propertyId);
        $response = new PropertyResource($soldProperty);

        return appResponse($request, $response);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        $this->propertyRepo->deleteById($id);

        return appResponse(Request(), [], Response::HTTP_NO_CONTENT);
    }
}
