<?php

namespace App\Services\OTP\Contract;

use App\Enums\OtpTypeEnum;
use App\Models\OtpCode;

interface OtpContractInterface
{
    public function generate(string $identifier, string $type = 'login', int $expiryMinutes = OtpCode::EXPIRE_MIN): string;
    public function verify(string $identifier, string $otpCode, string $type = 'login'): OtpCode|null;

}
