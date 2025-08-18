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
        'name', 'clinic_name', 'vat_number', 'email', 'phone', 'password', 'status'
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
}
