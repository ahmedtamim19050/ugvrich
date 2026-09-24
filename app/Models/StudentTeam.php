<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** A student team working on an idea or project, with its faculty supervisor. */
class StudentTeam extends Model
{
    use HasTranslations;

    /** Fields with a `_bn` twin; see the HasTranslations trait. */
    protected array $translatable = [
        'name',
        'notes',
    ];

    protected $guarded = [];

    protected $casts = [
        'members' => 'array',
        'is_active' => 'boolean',
    ];

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(Expert::class, 'supervisor_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
