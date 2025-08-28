<?php

namespace App\Http\Controllers\User\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Pipelines\Auth\GenerateOtpCode;
use App\Http\Pipelines\Auth\SendOtpNotification;
use App\Http\Pipelines\Auth\CheckPhoneExisting;
use App\Http\Requests\User\Auth\LoginRequest;
use App\Http\Requests\User\Auth\PhoneVerificationRequest;
use App\Http\Repositories\Contracts\UserRepositoryInterface;
use App\Http\Pipelines\Auth\PhoneVerificationPipeline;
use App\Http\Pipelines\Auth\LoginPipeline;
use App\Services\OTP\OtpService;
use App\Helpers\ApiResponse;
use App\Enums\OtpTypeEnum;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    public function __construct(
        protected OtpService $otpService,
        protected UserRepositoryInterface $userRepository,
        protected Pipeline $pipeline,
        protected LoginPipeline $loginPipeline
    )
    {
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        // Execute the login pipeline
        return $this->loginPipeline->execute([
            'credentials' => $credentials
        ]);
    }

    /**
     * Check Phone Exists and Send OTP to login
     */
    public function verifyPhone(PhoneVerificationRequest $request): JsonResponse
    {
        $data = $request->validated();

        return $this->pipeline
            ->send($data)
            ->through([
                CheckPhoneExisting::class,
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
    }
}
