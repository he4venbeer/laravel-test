<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ValidationException extends Exception
{
    public array $error;

    /**
     * @param Validator $validator
     */
    public function __construct(Validator $validator)
    {
        parent::__construct(
            "The given data is invalid",
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $this->error = $validator->errors()->messages();
    }

    /**
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        return new JsonResponse([
            'error' => true,
            'message' => $this->message,
            'data' => $this->error
        ], $this->code);
    }
}
