<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\DoctorStatusEnum;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Doctor extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'speciality_id',
        'status',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'status' => DoctorStatusEnum::class,
        'password' => 'hashed',
    ];

    /**
     * Get the speciality that the doctor belongs to.
     */
    public function speciality(): BelongsTo
    {
        return $this->belongsTo(Speciality::class);
    }
}
