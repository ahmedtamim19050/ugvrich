<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InnovationArea extends Model
{
    protected $guarded = [];

    protected $casts = [
        'focus' => 'array',
        'is_active' => 'boolean',
    ];

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

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
