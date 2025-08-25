<?php

namespace App\Services\OTP;

use App\Services\OTP\Contract\GenerateOtpInterface;
use Random\RandomException;

class GenerateNumericOTP implements GenerateOtpInterface
{

    /**
     * Generate a new numeric OTP code.
     *
     * @param int $length
     * @return string
     * @throws RandomException
     */
    public function generate(int $length = 4): string
    {
        return random_int(1111, 9999);
    }

}
