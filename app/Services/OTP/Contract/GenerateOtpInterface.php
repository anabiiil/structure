<?php

namespace App\Services\OTP\Contract;

use App\Enums\OtpTypeEnum;
use App\Models\OtpCode;

interface GenerateOtpInterface
{
    /**
     * Generate a new OTP code.
     *
     * @param int $length
     */
    public function generate(int $length = 6): string;
}
