<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Enums\MainUserStatusEnum;

class Clinic extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'clinic_name', 'vat_number', 'email', 'license_number', 'phone', 'whatsapp_number', 'google_map_location_url', 'purpose_note', 'password', 'status'
    ];

    protected $hidden = [
        'password', 'remember_token'
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => MainUserStatusEnum::class,
        ];
    }

    public function specialities(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Speciality::class, 'clinic_specialities')->withTimestamps();
    }

    public function branches(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Branch::class);
    }

    public function services(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ClinicService::class);
    }

    public function info(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ClinicInfo::class);
    }
}
