<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use App\Support\Vocabulary;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InnovationArea extends Model
{
    use HasTranslations;

    /** Fields with a `_bn` twin; see the HasTranslations trait. */
    protected array $translatable = [
        'name',
        'description',
        'focus',
    ];

    protected $guarded = [];

    protected $casts = [
        'focus' => 'array',
        'focus_bn' => 'array',
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
        return Vocabulary::label('departments', $this->department);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
