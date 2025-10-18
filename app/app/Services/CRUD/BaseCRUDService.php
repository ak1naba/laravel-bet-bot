<?php

namespace App\Services\CRUD;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class BaseCRUDService
{
    protected Model $model;

    abstract protected function modelClass(): string;

    public function __construct()
    {
        $this->model = app($this->modelClass());
    }

    protected function newQuery(): Builder
    {
        return $this->model->newQuery();
    }

    public function indexPaginate(array $params)
    {
        return $this->newQuery()->paginate($params['count_on_page'] ?? -1);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(Model $instance, array $data): Model
    {
        $instance->update($data);
        return $instance;
    }

    public function delete(Model $instance): bool
    {
        return $instance->delete();
    }
}


