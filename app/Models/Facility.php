<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use App\Support\Vocabulary;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasTranslations;

    /** Fields with a `_bn` twin; see the HasTranslations trait. */
    protected array $translatable = [
        'name',
        'description',
        'location',
        'equipment',
        'services',
    ];

    protected $guarded = [];

    protected $casts = [
        'equipment' => 'array',
        'equipment_bn' => 'array',
        'services' => 'array',
        'services_bn' => 'array',
        'is_bookable' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true);
    }

    public function getDepartmentNameAttribute(): ?string
    {
        return Vocabulary::label('departments', $this->department);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
