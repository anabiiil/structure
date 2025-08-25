<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Enums\OtpTypeEnum;
use Random\RandomException;

class OtpCode extends Model
{
    public const EXPIRE_MIN = 10;
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'phone',
        'code',
        'type',
        'expires_at',
        'is_used',
        'attempts',
        'verified_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
        'is_used' => 'boolean',
        'type' => OtpTypeEnum::class,
    ];

    /**
     * Check if OTP is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check if OTP is valid (not expired, not used, not verified)
     */
    public function isValid(): bool
    {
        return !$this->isExpired() && !$this->is_used && !$this->verified_at;
    }

    /**
     * Mark OTP as verified
     */
    public function markAsVerified(): bool
    {
        return $this->update([
            'is_used' => true,
            'verified_at' => now(),
        ]);
    }

    /**
     * Increment attempts counter
     */
    public function incrementAttempts(): bool
    {
        return $this->increment('attempts');
    }

    /**
     * Scope to get valid OTPs
     */
    public function scopeValid($query)
    {
        return $query->where('expires_at', '>', now())
                    ->where('is_used', false)
                    ->where('is_verified', false);
    }

    /**
     * Scope to get OTPs by phone and type
     */
    public function scopeForPhone($query, string $phone, OtpTypeEnum $type = OtpTypeEnum::VERIFICATION)
    {
        return $query->where('phone', $phone)->where('type', $type);
    }

    /**
     * Generate a random 6-digit OTP code
     * @throws RandomException
     */
    public static function generateCode(): string
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Create a new OTP for a phone number
     * @throws RandomException
     */
    public static function createForPhone(
        string $phone,
        OtpTypeEnum $type = OtpTypeEnum::VERIFICATION,
        int $expiresInMinutes = 10
    ): self {
        // Invalidate any existing valid OTPs for this phone and type
        self::forPhone($phone, $type)->valid()->update(['is_used' => true]);

        return self::create([
            'phone' => $phone,
            'code' => self::generateCode(),
            'type' => $type,
            'expires_at' => now()->addMinutes($expiresInMinutes),
        ]);
    }
}
