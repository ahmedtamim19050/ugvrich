<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CoreArea extends Model
{
    use HasTranslations;

    /** Fields with a `_bn` twin; see the HasTranslations trait. */
    protected array $translatable = [
        'title',
        'tagline',
        'description',
        'items',
    ];

    protected $guarded = [];

    protected $casts = [
        'items' => 'array',
        'items_bn' => 'array',
        'is_active' => 'boolean',
    ];

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
