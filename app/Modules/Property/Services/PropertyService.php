<?php

namespace App\Modules\Property\Services;

use App\Modules\Property\Models\Property;
use App\Modules\Property\Repos\PropertyRepo;
use Exception;
use Illuminate\Support\Facades\DB;

class PropertyService
{
    public function __construct(
        protected PropertyRepo $propertyRepo
    ){
    }

    /**
     * @param array $data
     * @return Property
     * @throws Exception
     */
    public function create(array $data): Property
    {
        // Business Logic Validate
        $this->validate($data);

        // Prefill
        $data = $this->prefill($data);

        DB::beginTransaction();

        try {
            /** @var Property $property */
            $property = $this->propertyRepo->create($data);

            // Callback
            $this->afterCreate($property);

            DB::commit();

            return $property;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param array $data
     * @return bool
     * @throws Exception
     */
    protected function validate(array $data): bool
    {
        if (empty($data)) {
            throw new Exception('Data to create cannot be empty');
        }

        return true;
    }

    /**
     * @param array $data
     * @return array
     */
    protected function prefill(array $data): array
    {
        $data['is_sold'] = false;

        return $data;
    }

    /**
     * @param Property $property
     * @return void
     */
    protected function afterCreate(Property $property): void
    {
        // Do something after property created
    }
}
