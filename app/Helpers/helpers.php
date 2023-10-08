<?php

use App\Helpers\ResponseMapper;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

if (!function_exists('appResponse')) {

    function appResponse(
        Request $request,
        mixed $content = null,
        $status = 200,
        array $headers = []
    ): ResponseMapper|JsonResponse{
        $factory = new App\Helpers\ResponseMapper();

        return $factory->createResponse($request, $content, $status, $headers);
    }
}
