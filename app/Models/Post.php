<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasTranslations;

    /** Fields with a `_bn` twin; see the HasTranslations trait. */
    protected array $translatable = [
        'title',
        'excerpt',
        'body',
        'category',
        'location',
        'author',
    ];

    protected $guarded = [];

    protected $casts = [
        'event_at' => 'datetime',
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
    ];

    public function scopePublished(Builder $q): Builder
    {
        return $q->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    public function scopeNews(Builder $q): Builder
    {
        return $q->where('type', 'news');
    }

    public function scopeEvents(Builder $q): Builder
    {
        return $q->where('type', 'event');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getReadingTimeAttribute(): int
    {
        return max(1, (int) ceil(str_word_count(strip_tags((string) $this->body)) / 200));
    }
}
