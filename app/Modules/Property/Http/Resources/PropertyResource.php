<?php

namespace App\Modules\Property\Http\Resources;

use App\Http\AppJsonResource;

class PropertyResource extends AppJsonResource
{
    /**
     * @param $request
     * @return array
     */
    public function internalData($request): array
    {
        return [
            'id'    => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'is_sold' => $this->is_sold,
            'owner_id' => $this->owner_id,
            'created_at' => $this->created_at,
        ];
    }

    /**
     * Only key that need to serve to public
     *
     * @param $request
     * @return array
     */
    public function publicData($request): array
    {
        return [
            'id'    => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'is_sold' => $this->is_sold,
            'owner_id' => $this->owner_id,
            'created_at' => $this->created_at,
        ];
    }
}
