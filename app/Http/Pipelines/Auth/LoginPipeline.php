<?php

namespace App\Http\Pipelines\Auth;

use App\Http\Pipelines\Auth\ValidateUserCredentials;
use App\Http\Pipelines\Auth\ValidatePassword;
use App\Http\Pipelines\Auth\ValidateUserStatus;
use App\Http\Pipelines\Auth\GenerateAuthToken;
use App\Helpers\ApiResponse;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Http\JsonResponse;

class LoginPipeline
{
    public function __construct(
        protected Pipeline $pipeline
    ) {
    }

    /**
     * Execute the login pipeline
     */
    public function execute(array $data): JsonResponse
    {
        try {
            $result = $this->pipeline
                ->send($data)
                ->through([
                    ValidateUserCredentials::class,
                    ValidatePassword::class,
                    ValidateUserStatus::class,
                    GenerateAuthToken::class,
                ])
                ->then(function ($data) {
                    // Final step - return success response
                    return ApiResponse::success([
                        'user' => $data['user'],
                        'token' => $data['token'],
                        'message' => 'Login successful'
                    ]);
                });

            return $result;
        } catch (\Illuminate\Http\Exceptions\HttpResponseException $e) {
            return $e->getResponse();
        }
    }
}
