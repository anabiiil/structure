<?php

namespace App\Http\Pipelines\Auth;

use App\Http\Repositories\Contracts\UserRepositoryInterface;
use App\Helpers\ApiResponse;
use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;

class CheckPhoneExisting
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {
    }

    public function handle(array $data, Closure $next)
    {
        $phone = $data['phone'];

        // Check if user exists
        $user = $this->userRepository->findByPhone($phone);

        if (!$user) {
            throw new HttpResponseException(
                ApiResponse::error('Phone number not found', 404)
            );
        }

        // return user with data returned to next step
        $data['user'] = $user;

        return $next($data);
    }
}
