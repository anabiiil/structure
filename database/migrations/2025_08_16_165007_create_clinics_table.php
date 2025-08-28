<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clinics', function (Blueprint $table) {
            $table->id();
            $table->string('name', 220);
            $table->string('clinic_name', 220);
            $table->string('vat_number', 50)->nullable()->unique();
            $table->string('email')->unique();
            $table->string('license_number', 100)->nullable();
            $table->string('phone', 30)->nullable()->unique();
            $table->string('whatsapp_number', 30)->nullable();
            $table->text('google_map_location_url')->nullable();
            $table->text('purpose_note')->nullable();
            $table->string('password')->nullable();
            $table->string('status', 30)->default('pending');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinics');
    }
};
