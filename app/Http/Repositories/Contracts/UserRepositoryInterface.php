<?php

namespace App\Http\Repositories\Contracts;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    /**
     * Find a user by email address.
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User;

    /**
     * Find a user by a specific column.
     *
     * @param string $column
     * @param mixed $value
     * @return User|null
     */
    public function findByCol(string $column, mixed $value): ?User;

    /**
     * Find a user by phone number.
     *
     * @param string $phone
     * @return User|null
     */
    public function findByPhone(string $phone): ?User;

    /**
     * Get all active users.
     *
     * @param array<string> $cols Columns to select
     * @param array<string> $relations Relations to eager load
     * @return Collection<int, User>
     */
    public function getActiveUsers(array $cols = ['*'], array $relations = []): Collection;
}
