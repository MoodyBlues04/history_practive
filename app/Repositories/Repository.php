<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class Repository
{
    protected Builder $query;

    public function __construct(Builder|Model|string $model)
    {
        if (is_string($model) || is_subclass_of($model, Model::class)) {
            $this->query = $model::query();
        } else {
            $this->query = $model;
        }
    }

    public function create(array $attributes): Builder|Model
    {
        return $this->query->create($attributes);
    }

    public function update(array $attributes, Model $model): bool
    {
        return $model->update($attributes);
    }

    public function firstBy(array $params): ?Model
    {
        return $this->query->where($params)->first();
    }

    /**
     * @return Model[]
     */
    public function getAll(): array
    {
        return $this->query->get()->all();
    }

    public function getById(int $id): ?Model
    {
        return $this->firstBy(['id' => $id]);
    }
}
