<?php

namespace App\Http\Pipelines\Auth;

use Closure;

class GenerateAuthToken
{
    public function handle(array $data, Closure $next)
    {
        $user = $data['user'];

        // Generate authentication token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Add token to pipeline data
        $data['token'] = $token;

        return $next($data);
    }
}
