<?php

namespace App\Modules\Property\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Property\Repos\PropertyRepo;
use App\Modules\Property\Services\PropertyService;
use App\Modules\Property\Validations\PropertyValidation;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        // Todo - need to implement response handler and resource
        return response()->json(['success', $this->propertyRepo->list()]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function view(Request $request): JsonResponse
    {
        $property = $this->propertyRepo->findById($request->id);

        return response()->json(['success', $property]);
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

        return response()->json(['Property created successfully', $property]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function sell(Request $request): JsonResponse
    {
        $propertyId = $request->id;
        $soldProperty = $this->propertyRepo->sell($propertyId);

        return response()->json(['Property is sold', $soldProperty]);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        // $propertyId = $request->id;
        $this->propertyRepo->deleteById($id);

        return response()->json(['Property deleted']);
    }
}
