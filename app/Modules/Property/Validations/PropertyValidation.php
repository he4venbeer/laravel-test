<?php

namespace App\Modules\Property\Validations;

use App\Validations\AppValidation;

class PropertyValidation extends AppValidation
{
    /**
     * @return string[]
     */
    public function createRequest(): array
    {
        return [
            'name' => 'required|string',
            'address' => 'required|string',
            'owner_id' => 'required|integer|exists:owners,id',
        ];
    }
}
