<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\BranchServiceEnum;

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
        'service_type',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'service_type' => BranchServiceEnum::class,
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function city(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(City::class);
    }

     relation: a branch can have many clinic services
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
