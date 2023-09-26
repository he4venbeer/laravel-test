<?php

namespace App\Repos;

use App\Models\AppModel;
use Illuminate\Support\Str;

abstract class AppRepo
{
    protected  string $modelVarName;

    /**
     * @return AppModel
     */
    protected function getModel(): AppModel
    {
        if (isset($this->model)) {
            return $this->model;
        }

        return $this->{$this->getModelVarName()};
    }

    /**
     * @return string
     */
    private function getModelVarName(): string
    {
        if (!empty($this->modelVarName)) {
            return $this->modelVarName;
        }

        $abstract = static::class;
        $class = class_basename($abstract);
        $model = Str::replace('Repo', '', $class);
        $var = Str::camel($model);

        $this->modelVarName = $var;

        return $this->modelVarName;
    }

    /**
     * @param string $name
     * @return AppModel
     */
    public function __get(string $name)
    {
        if ($name !== 'model') {
            return $this->{$name};
        }

        if (isset($this->model)) {
            return $this->model;
        }

        return $this->getModel();
    }
}
