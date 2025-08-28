<?php

namespace App\Http\Pipelines\Auth;

use Closure;
use Illuminate\Support\Facades\Log;

class SendOtpNotification
{
    public function handle(array $data, Closure $next)
    {
        $phone = $data['phone'];
        $otpCode = $data['otp_code'];

        // Here you would integrate with SMS service (Twilio, AWS SNS, etc.)
        // For now, we'll just log it
        Log::info("OTP Code generated for phone: {$phone}", [
            'phone' => $phone,
            'otp_length' => strlen($otpCode),
            'timestamp' => now()
        ]);

        // In production, you'd do something like:
        // $this->smsService->send($phone, "Your OTP code is: {$otpCode}");

        return $next($data);
    }
}
