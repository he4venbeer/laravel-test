<?php

namespace App\Modules\Property\Repos;

use App\Repos\AppRepo;
use App\Repos\CRUDtrait;
use App\Modules\Property\Models\Property;
use Illuminate\Database\Eloquent\Collection;

class PropertyRepo extends AppRepo
{
    use CRUDtrait;

    public function __construct(
        protected Property $model
    ){
    }

    public function list(): Collection
    {
        return $this->model->get();
    }

    public function sell(int $id): Property
    {
        return $this->updateById($id, ['is_sold' => true]);
    }

}
