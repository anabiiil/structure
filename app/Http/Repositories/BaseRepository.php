<?php

namespace App\Http\Repositories;

use App\Http\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository implements BaseRepositoryInterface
{
    /**
     * The model instance.
     *
     * @var Model
     */
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function builder(
        array $cols = ['*'],
        array $relations = [],
        array $conditions = [],
        string $order = 'asc',
        string $orderCol = 'id'
    ): Builder {
        return $this->model->newQuery()
            ->select($cols)
            ->with($relations)
            ->where($conditions)
            ->orderBy($orderCol, $order);
    }

    public function paginate(
        array $cols = ['*'],
        array $relations = [],
        array $conditions = [],
        string $order = 'asc',
        string $orderCol = 'id',
        int $perPage = 15
    ): LengthAwarePaginator {
        return $this->builder($cols, $relations, $conditions, $order, $orderCol)->paginate($perPage);
    }

    public function get(
        array $cols = ['*'],
        array $relations = [],
        array $conditions = [],
        string $order = 'asc',
        string $orderCol = 'id'
    ): Collection {
        return $this->builder($cols, $relations, $conditions, $order, $orderCol)->get();
    }

    public function findWhere(array $conditions, array $cols = ['*'], array $relations = []): ?Model
    {
        return $this->builder($cols, $relations, $conditions)->first();
    }

    public function store(array $data): ?Model
    {
        return $this->model->create($data);
    }

    public function createMany(array $data): Collection
    {
        return $this->model->insert($data);
    }
    public function updateWhere(array $conditions, array $data): ?Model
    {
        $model = $this->builder($conditions)->first();
        if (!$model) {
            return null;
        }
        $model->update($data);
        return $model;
    }

    /**
     * Delete models matching conditions.
     *
     * @param int $id
     * @param array $data
     * @return Model|null
     */

    public function update(int $id, array $data): ?Model
    {
        $model = $this->model->find($id);
        if (!$model) {
            return null;
        }
        $model->update($data);
        return $model;
    }

}
