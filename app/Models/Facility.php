<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $guarded = [];

    protected $casts = [
        'equipment' => 'array',
        'services' => 'array',
        'is_bookable' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true);
    }

    public function getDepartmentNameAttribute(): ?string
    {
        return config('rich.departments.'.$this->department);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
