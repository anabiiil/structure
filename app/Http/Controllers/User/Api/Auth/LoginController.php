<?php

namespace App\Http\Controllers\User\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Auth\LoginRequest;
use App\Http\Requests\User\Auth\PhoneVerificationRequest;
use App\Services\OTP\OtpService;
use App\Helpers\ApiResponse;
use App\Enums\OtpTypeEnum;
use App\Models\User;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function __construct(
        protected OtpService $otpService

    )
    {
    }

    public function login(LoginRequest $request)
    {

    }

    /**
     * Check if phone exists and send OTP for authentication
     */
    public function verifyPhone(PhoneVerificationRequest $request)
    {


        try {
            $phone = $request->validated()['phone'];

            // Find user by phone (validation already ensures it exists)
            $user = User::where('phone', $phone)->first();

            if (!$user) {
                return ApiResponse::error('Phone number not found', 404);
            }

            // Generate OTP for login
            $otpCode = $this->otpService->generate(
                identifier: $phone,
                type: OtpTypeEnum::LOGIN->value
            );

            // In a real application, you would send the OTP via SMS
            // For now, we'll return it in the response (remove this in production)
            return ApiResponse::success([
                'message' => 'OTP sent successfully to your phone number',
                'phone' => $phone,
                'otp' => $otpCode, // Remove this line in production
                'expires_in_minutes' => 5
            ]);

        } catch (\Exception $e) {
            return ApiResponse::error('Failed to send OTP. Please try again.', 500);
        }
    }



}
