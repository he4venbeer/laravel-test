<?php

namespace App\Validations;

use App\Exceptions\ValidationException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AppValidation
{

    /**
     * @param Request $request
     * @param string $ruleName
     * @param array $message
     * @return array
     * @throws Exception
     */
    public function validateRequest(Request $request, string $ruleName, array $message = []): array
    {
        $ruleName = $ruleName . 'Request';

        if (!method_exists($this, $ruleName)) {
            throw new Exception( "Validation request method for $ruleName is not found");
        }

        $rules = $this->$ruleName($request);
        $message = array_merge($this->message(), $message);
        $validate = Validator::make($request->all(), $rules, $message);

        if ($validate->fails()) {
            throw new ValidationException(
                $validate,
            );
        }

        return $request->only(collect($rules)->keys()->map(function ($rule) {
            return Str::contains($rule, '.') ? explode('.', $rule[0]) : $rule;
        })->unique()->toArray());
    }

    /**
     * @return array
     */
    protected function message(): array
    {
        return [];
    }
}
