<?php

namespace App\Http\Pipelines\Auth;

use App\Services\OTP\OtpService;
use App\Enums\OtpTypeEnum;
use App\Helpers\ApiResponse;
use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;

class GenerateOtpCode
{
    public function __construct(
        protected OtpService $otpService
    ) {
    }

    public function handle(array $data, Closure $next)
    {
        $phone = $data['phone'];

        // Generate OTP Code
        $otpCode = $this->otpService->generate($phone, OtpTypeEnum::LOGIN->value);


        // Add OTP code to pipeline data
        $data['otp_code'] = $otpCode;

        return $next($data);
    }
}
