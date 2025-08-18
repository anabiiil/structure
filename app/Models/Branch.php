<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'name',
        'clinic_id',
        'status',
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    // Optional scope for active branches
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
