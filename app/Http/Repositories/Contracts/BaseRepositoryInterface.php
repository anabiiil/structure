<?php

namespace App\Http\Repositories\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;

interface BaseRepositoryInterface
{
    /**
     * Get model builder with optional filtering, relations, and ordering.
     *
     * @param array<string> $cols Columns to select
     * @param array<string> $relations Relations to eager load
     * @param array<string, mixed> $conditions Where conditions
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
    ): Builder;

    /**
     * Get paginated model data with optional filtering, relations, and ordering.
     *
     * @param array<string> $cols Columns to select
     * @param array<string> $relations Relations to eager load
     * @param array<string, mixed> $conditions Where conditions
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
    ): LengthAwarePaginator;

    /**
     * Get all model data with optional filtering, relations, and ordering.
     *
     * @param array<string> $cols Columns to select
     * @param array<string> $relations Relations to eager load
     * @param array<string, mixed> $conditions Where conditions
     * @param string $order Sort order (asc|desc)
     * @param string $orderCol Column to sort by
     * @return Collection<int, Model>
     */
    public function get(
        array $cols = ['*'],
        array $relations = [],
        array $conditions = [],
        string $order = 'asc',
        string $orderCol = 'id'
    ): Collection;


    /**
     * Find a single model by conditions.
     *
     * @param array<string, mixed> $conditions Where conditions
     * @param array<string> $cols Columns to select
     * @param array<string> $relations Relations to eager load
     * @return Model|null
     */
    public function findWhere(array $conditions, array $cols = ['*'], array $relations = []): ?Model;

    /**
     * Store a new model.
     *
     * @param array<string, mixed> $data Model attributes
     * @return Model|null
     */
    public function store(array $data): ?Model;

    /**
     * Create multiple models.
     *
     * @param array<array<string, mixed>> $data Array of model attributes
     * @return Collection<int, Model>
     */
    public function createMany(array $data): Collection;

    /**
     * Update a model by ID.
     *
     * @param int $id Model ID
     * @param array<string, mixed> $data Updated attributes
     * @return Model|null
     */
    public function update(int $id, array $data): ?Model;

    /**
     * Update models matching conditions.
     *
     * @param array<string, mixed> $conditions Where conditions
     * @param array<string, mixed> $data Updated attributes
     * @return Model|null Number of affected rows
     */
    public function updateWhere(array $conditions, array $data): ?Model;


    /**
     * Update or create a model.
     *
     * @param array<string, mixed> $conditions Conditions to find existing model
     * @param array<string, mixed> $data Data to update or create with
     * @return Model
     */
    public function updateOrCreate(array $conditions, array $data = []): Model;

    /**
     * Delete models matching conditions.
     *
     * @param array<string, mixed> $conditions Where conditions
     * @return bool
     */
    public function destroyWhere(array $conditions): bool;

    /**
     * Delete model with conditions (alias for destroyWhere).
     *
     * @param array<string, mixed> $conditions Where conditions
     * @return bool
     */
    public function destroyWithCondition(array $conditions): bool;

    /**
     * Force delete a model by ID (for soft deletes).
     *
     * @param int $id Model ID
     * @return bool
     */
    public function forceDestroy(int $id): bool;

    /**
     * Restore a soft deleted model by ID.
     *
     * @param int $id Model ID
     * @return bool
     */
    public function restore(int $id): bool;

    /**
     * Get the first model or create it.
     *
     * @param array<string, mixed> $conditions Conditions to find existing model
     * @param array<string, mixed> $data Data to create with if not found
     * @return Model
     */
    public function firstOrCreate(array $conditions, array $data = []): Model;

}
