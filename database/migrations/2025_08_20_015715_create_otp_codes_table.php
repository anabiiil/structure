<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\OtpTypeEnum;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('otp_codes', static function (Blueprint $table) {
            $table->id();
            $table->string('phone', 20)->index(); // Phone number to send OTP to
            $table->string('code', 6); // 6-digit OTP code
            $table->enum('type', OtpTypeEnum::values())->default(OtpTypeEnum::VERIFICATION->value); // Purpose of OTP
            $table->timestamp('expires_at'); // When the OTP expires
            $table->integer('attempts')->default(0); // Number of attempts to verify this OTP
            $table->boolean('is_used')->default(false); // Whether OTP has been used
            $table->timestamp('verified_at')->nullable(); // When OTP was verified
            $table->timestamps();
            // Indexes for better performance
            $table->index(['phone', 'type']);
            $table->index(['code', 'expires_at']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otp_codes');
    }
};
