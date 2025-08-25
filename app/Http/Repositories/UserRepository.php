<?php

namespace App\Http\Repositories;

use App\Http\Repositories\Contracts\UserRepositoryInterface;
use App\Models\User;
use App\Enums\MainUserStatusEnum;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 *
 */
class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * UserRepository constructor.
     */
    public function __construct(protected User $user)
    {
        parent::__construct($user);
    }

    /**
     * Find a user by email address.
     *
     * @param string $email
     * @return Model|null
     */
    public function findByEmail(string $email): ?User
    {
        return $this->findWhere(['email' => $email]);
    }

    /**
     * Find a user by a specific column.
     *
     * @param string $column
     * @param mixed $value
     * @return Model|null
     */
    public function findByCol(string $column, mixed $value): ?User
    {
        return $this->findWhere([$column => $value]);
    }

    /**
     * Find a user by phone number.
     *
     * @param string $phone
     * @return Model|null
     */
    public function findByPhone(string $phone): ?User
    {
        return $this->findWhere(['phone' => $phone]);
    }

    /**
     * Get all active users.
     *
     * @param array<string> $cols Columns to select
     * @param array<string> $relations Relations to eager load
     * @return Collection<int, User>
     */
    public function getActiveUsers(array $cols = ['*'], array $relations = []): Collection
    {
        return $this->get($cols, $relations, ['status' => MainUserStatusEnum::ACTIVE]);
    }
    }
