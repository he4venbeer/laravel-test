<?php

namespace App\Repos;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Model;

trait CRUDtrait
{

    /**
     * @param int $id
     * @return Model
     */
    public function findOrFail(int $id): Model
    {
        return $this->getModel()->findOrFail($id);
    }

    /**
     * @param int|string $modelId
     * @param bool $strict
     * @return Model|null
     */
    public function findById(int|string $modelId, bool $strict = true): ?Model
    {
        if ($strict) {
            return $this->getModel()->findOrFail($modelId);
        }

        return $this->getModel()->where('id', $modelId)->first();
    }

    /**
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model
    {
        return $this->getModel()->create($data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return AppModel
     */
    public function updateById(int $id, array $data): AppModel
    {
        $record = $this->getModel()->findOrFail($id);
        $record->update($data);

        return $record;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function deleteById(int $id): bool
    {
        return $this->getModel()->findOrFail($id)->delete();
    }
}
