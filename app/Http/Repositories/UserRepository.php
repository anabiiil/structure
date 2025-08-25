<?php

namespace App\Http\Repositories;

use App\Http\Repositories\Contracts\UserRepositoryInterface;
use App\Models\User;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * UserRepository constructor.
     */
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    /**
     * Find user by email.
     */
    public function findByEmail(string $email): ?User
    {
        return $this->findBy(['email' => $email]);
    }

    /**
     * Find user by phone.
     */
    public function findByPhone(string $phone): ?User
    {
        return $this->findBy(['phone' => $phone]);
    }

    /**
     * Get active users.
     */
    public function getActiveUsers(array $cols = ['*'], array $relations = []): \Illuminate\Database\Eloquent\Collection
    {
        return $this->all($cols, $relations, ['status' => 'active']);
    }

    /**
     * Search users by name or email.
     */
    public function searchUsers(string $search, int $perPage = 15): \Illuminate\Pagination\LengthAwarePaginator
    {
        return $this->getModel()
            ->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%");
            })
            ->paginate($perPage);
    }

    /**
     * Get users by gender.
     */
    public function getUsersByGender(string $gender): \Illuminate\Database\Eloquent\Collection
    {
        return $this->all(['*'], [], ['gender' => $gender]);
    }
}
