<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
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

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    // Optional scope for active branches
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
