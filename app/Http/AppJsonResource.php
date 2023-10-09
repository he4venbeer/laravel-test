<?php

namespace App\Http;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class AppJsonResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        if (isInternalRequest($request)) {
            return $this->internalData($request);
        }

        return $this->publicData($request);
    }

    /**
     * @param Request $request
     * @return array
     */
    abstract function internalData(Request $request): array;

    /**
     * @param Request $request
     * @return array
     */
    abstract function publicData(Request $request): array;
}
