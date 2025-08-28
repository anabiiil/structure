<?php

namespace App\Http\Pipelines\Auth;

use App\Helpers\ApiResponse;
use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Hash;

class ValidatePassword
{
    public function handle(array $data, Closure $next)
    {
        $user = $data['user'];
        $credentials = $data['credentials'];

        if (!Hash::check($credentials['password'], $user->password)) {
            throw new HttpResponseException(
                ApiResponse::error('Invalid credentials', 401)
            );
        }

        return $next($data);
    }
}
