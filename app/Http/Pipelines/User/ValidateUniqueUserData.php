<?php

namespace App\Http\Pipelines\User;

use App\Http\Repositories\Contracts\UserRepositoryInterface;
use App\Helpers\ApiResponse;
use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ValidateUniqueUserData
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {
    }

    public function handle(array $data, Closure $next)
    {
        $userData = $data['user_data'];
        $currentUserId = $data['current_user_id'] ?? null;

        // Check email uniqueness
        if (isset($userData['email'])) {
            $existingUser = $this->userRepository->findByEmail($userData['email']);
            if ($existingUser && $existingUser->id !== $currentUserId) {
                throw new HttpResponseException(
                    ApiResponse::error('Email already exists', 409)
                );
            }
        }

        // Check phone uniqueness
        if (isset($userData['phone'])) {
            $existingUser = $this->userRepository->findByPhone($userData['phone']);
            if ($existingUser && $existingUser->id !== $currentUserId) {
                throw new HttpResponseException(
                    ApiResponse::error('Phone already exists', 409)
                );
            }
        }

        return $next($data);
    }
}
