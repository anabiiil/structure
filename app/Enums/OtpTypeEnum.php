<?php

namespace App\Enums;

enum OtpTypeEnum: string
{
    case REGISTRATION = 'registration';
    case LOGIN = 'login';
    case PASSWORD_RESET = 'password_reset';
    case VERIFICATION = 'verification';

    /**
     * Get all enum values as array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get enum labels for display
     */
    public function label(): string
    {
        return match($this) {
            self::REGISTRATION => 'Registration',
            self::LOGIN => 'Login',
            self::PASSWORD_RESET => 'Password Reset',
            self::VERIFICATION => 'Verification',
        };
    }
}
