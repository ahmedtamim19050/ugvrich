<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    protected $guarded = [];

    protected $casts = [
        'gallery' => 'array',
        'team_members' => 'array',
        'technologies' => 'array',
        'is_featured' => 'boolean',
        'budget' => 'decimal:2',
        'start_date' => 'date',
        'deadline' => 'date',
        'progress' => 'integer',
    ];

    protected static function booted(): void
    {
        // Every project gets a permanent ID such as RICH-CSE-2026-001.
        static::creating(function (Project $project) {
            if (blank($project->code)) {
                $project->code = static::nextCode($project->department, $project->start_date?->year ?? (int) ($project->year ?: date('Y')));
            }
        });
    }

    public static function nextCode(?string $department, int $year): string
    {
        $prefix = sprintf('RICH-%s-%d-', strtoupper($department ?: 'RICH'), $year);

        $last = static::where('code', 'like', $prefix.'%')->orderByDesc('code')->value('code');
        $next = $last ? ((int) substr($last, strlen($prefix))) + 1 : 1;

        return $prefix.str_pad((string) $next, 3, '0', STR_PAD_LEFT);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function innovationArea(): BelongsTo
    {
        return $this->belongsTo(InnovationArea::class);
    }

    public function scopeFeatured(Builder $q): Builder
    {
        return $q->where('is_featured', true);
    }

    public function scopeOfType(Builder $q, ?string $type): Builder
    {
        return $type ? $q->where('type', $type) : $q;
    }

    public function getDepartmentNameAttribute(): ?string
    {
        return config('rich.departments.'.$this->department);
    }

    public function getTypeLabelAttribute(): string
    {
        return config('rich.project_types.'.$this->type, ucfirst((string) $this->type));
    }

    public function getStageLabelAttribute(): ?string
    {
        return config('rich.pipeline_stages.'.$this->stage);
    }

    /** 1-based position of the current stage in the pipeline, or 0. */
    public function getStageIndexAttribute(): int
    {
        $position = array_search($this->stage, array_keys(config('rich.pipeline_stages')), true);

        return $position === false ? 0 : $position + 1;
    }

    public function getPatentStatusLabelAttribute(): string
    {
        return config('rich.patent_statuses.'.$this->patent_status, 'Not applicable');
    }

    public function getCommercializationStatusLabelAttribute(): string
    {
        return config('rich.commercialization_statuses.'.$this->commercialization_status, 'Not started');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
