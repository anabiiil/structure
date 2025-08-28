<?php

namespace App\Http\Pipelines\Auth;

use App\Http\Repositories\Contracts\UserRepositoryInterface;
use App\Helpers\ApiResponse;
use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;

class ValidateUserCredentials
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {
    }

    public function handle(array $data, Closure $next)
    {
        $credentials = $data['credentials'];

        // Find user by email or phone
        $user = isset($credentials['email'])
            ? $this->userRepository->findByEmail($credentials['email'])
            : $this->userRepository->findByPhone($credentials['phone']);

        if (!$user) {
            throw new HttpResponseException(
                ApiResponse::error('Invalid credentials', 401)
            );
        }

        // Add user to pipeline data
        $data['user'] = $user;

        return $next($data);
    }
}
