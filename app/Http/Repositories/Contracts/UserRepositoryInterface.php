<?php

namespace App\Http\Repositories\Contracts;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Find user by email.
     */
    public function findByEmail(string $email): ?User;

    /**
     * Find user by phone.
     */
    public function findByPhone(string $phone): ?User;

    /**
     * Get active users.
     */
    public function getActiveUsers(array $cols = ['*'], array $relations = []): Collection;

    /**
     * Search users by name or email.
     */
    public function searchUsers(string $search, int $perPage = 15): LengthAwarePaginator;

    /**
     * Get users by gender.
     */
    public function getUsersByGender(string $gender): Collection;
}
