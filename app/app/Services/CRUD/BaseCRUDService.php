<?php

namespace App\Services\CRUD;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    public function getInstance(Model $instance)
    {
        return $instance;
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

    public function forceDelete(Model $instance): bool
    {
        if (in_array(SoftDeletes::class, class_uses_recursive($instance))) {
            return $instance->forceDelete();
        }

        return $instance->delete();
    }

    public function restore(int|string|Model $instance): ?Model
    {
        if (is_numeric($instance)) {
            $instance = $this->model->onlyTrashed()->findOrFail($instance);
        }

        if (in_array(SoftDeletes::class, class_uses_recursive($instance))) {
            $instance->restore();
            return $instance;
        }

        Log::warning('Попытка восстановления модели, не использующей SoftDeletes: ' . get_class($instance));
        return null;
    }
}


