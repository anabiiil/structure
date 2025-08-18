<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'clinic_id',
        'city_id',
        'status',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function city(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    // Added relation: a branch can have many clinic services
    public function clinicServices(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(ClinicService::class, 'branch_services')->withTimestamps();
    }

    // Optional scope for active branches
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
