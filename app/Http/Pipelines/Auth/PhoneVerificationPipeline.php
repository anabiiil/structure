<?php

namespace App\Http\Pipelines\Auth;

use App\Http\Pipelines\Auth\CheckPhoneExisting;
use App\Http\Pipelines\Auth\ValidateUserStatus;
use App\Http\Pipelines\Auth\GenerateOtpCode;
use App\Http\Pipelines\Auth\SendOtpNotification;
use App\Helpers\ApiResponse;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Http\JsonResponse;

class PhoneVerificationPipeline
{
    public function __construct(
        protected Pipeline $pipeline
    ) {
    }

    /**
     * Execute the phone verification pipeline
     */
    public function execute(array $data): JsonResponse
    {
        try {
            return $this->pipeline
                ->send($data)
                ->through([
                    CheckPhoneExisting::class,
                    ValidateUserStatus::class,
                    GenerateOtpCode::class,
                    SendOtpNotification::class,
                ])
                ->then(function ($data) {
                    // Final step - return success response
                    return ApiResponse::success([
                        'message' => 'OTP sent successfully',
                        'phone' => $data['phone'],
                        'user_id' => $data['user']->id
                    ]);
                });
        } catch (\Illuminate\Http\Exceptions\HttpResponseException $e) {
            return $e->getResponse();
        }
    }

    /**
     * Get the pipeline steps for inspection/debugging
     */
    public function getSteps(): array
    {
        return [
            'validate_phone' => CheckPhoneExisting::class,
            'validate_status' => ValidateUserStatus::class,
            'generate_otp' => GenerateOtpCode::class,
            'send_notification' => SendOtpNotification::class,
        ];
    }
}
