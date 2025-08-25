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

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get model builder with optional filtering, relations, and ordering.
     *
     * @param array $cols Columns to select
     * @param array $relations Relations to eager load
     * @param array $conditions Where conditions
     * @param string $order Sort order (asc|desc)
     * @param string $orderCol Column to sort by
     * @return Builder
     */
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

    /**
     * Get paginated model data with optional filtering, relations, and ordering.
     *
     * @param array $cols Columns to select
     * @param array $relations Relations to eager load
     * @param array $conditions Where conditions
     * @param string $order Sort order (asc|desc)
     * @param string $orderCol Column to sort by
     * @param int $perPage Number of items per page
     * @return LengthAwarePaginator
     */
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

    /**
     * Get all model data with optional filtering, relations, and ordering.
     *
     * @param array $cols Columns to select
     * @param array $relations Relations to eager load
     * @param array $conditions Where conditions
     * @param string $order Sort order (asc|desc)
     * @param string $orderCol Column to sort by
     * @return Collection
     */
    public function get(
        array $cols = ['*'],
        array $relations = [],
        array $conditions = [],
        string $order = 'asc',
        string $orderCol = 'id'
    ): Collection {
        return $this->builder($cols, $relations, $conditions, $order, $orderCol)->get();
    }

    /**
     * Find a single model by conditions.
     *
     * @param array $conditions Where conditions
     * @param array $cols Columns to select
     * @param array $relations Relations to eager load
     * @return Model|null
     */
    public function findWhere(array $conditions, array $cols = ['*'], array $relations = []): ?Model
    {
        return $this->builder($cols, $relations, $conditions)->first();
    }

    /**
     * Store a new model.
     *
     * @param array $data Model attributes
     * @return Model|null
     */
    public function store(array $data): ?Model
    {
        return $this->model->create($data);
    }

    /**
     * Create multiple models.
     *
     * @param array $data Array of model attributes
     * @return Collection
     */
    public function createMany(array $data): Collection
    {
        $models = [];
        foreach ($data as $item) {
            $models[] = $this->model->create($item);
        }
        return new Collection($models);
    }

    /**
     * Update a model by conditions.
     *
     * @param array $conditions Where conditions
     * @param array $data Updated attributes
     * @return Model|null
     */
    public function updateWhere(array $conditions, array $data): ?Model
    {
        $model = $this->builder(conditions:$conditions)->first();
        if (!$model) {
            return null;
        }
        $model->update($data);
        return $model;
    }

    /**
     * Update or create a model by conditions.
     *
     * @param array $conditions Where conditions
     * @param array $data Model attributes
     * @return Model
     */
    public function updateOrCreate(array $conditions, array $data = []): Model
    {
        return $this->model->updateOrCreate($conditions, $data);
    }

    /**
     * Delete models matching conditions.
     *
     * @param array $conditions Where conditions
     * @return bool
     */
    public function destroyWhere(array $conditions): bool
    {
        $query = $this->model->newQuery();
        foreach ($conditions as $col => $val) {
            $query->where($col, $val);
        }
        return (bool) $query->delete();
    }

    /**
     * Delete models matching conditions (alias for destroyWhere).
     *
     * @param array $conditions Where conditions
     * @return bool
     */
    public function destroyWithCondition(array $conditions): bool
    {
        return $this->destroyWhere($conditions);
    }

    /**
     * Force delete a model by ID (including soft deleted).
     *
     * @param int $id
     * @return bool
     */
    public function forceDestroy(int $id): bool
    {
        $model = $this->model->withTrashed()->find($id);
        if ($model) {
            return (bool) $model->forceDelete();
        }
        return false;
    }

    /**
     * Restore a soft deleted model by ID.
     *
     * @param int $id
     * @return bool
     */
    public function restore(int $id): bool
    {
        $model = $this->model->withTrashed()->find($id);
        if ($model) {
            return (bool) $model->restore();
        }
        return false;
    }

    /**
     * Find the first model matching attributes or create it.
     *
     * @param array $conditions Where conditions
     * @param array $data Model attributes
     * @return Model
     */
    public function firstOrCreate(array $conditions, array $data = []): Model
    {
        return $this->model->firstOrCreate($conditions, $data);
    }

    /**
     * Update a model by ID.
     *
     * @param int $id
     * @param array $data Updated attributes
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
