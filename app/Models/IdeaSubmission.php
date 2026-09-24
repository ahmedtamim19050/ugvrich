<?php

namespace App\Models;

use App\Support\Vocabulary;
use Illuminate\Database\Eloquent\Model;

class IdeaSubmission extends Model
{
    protected $guarded = [];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function getDepartmentNameAttribute(): ?string
    {
        return Vocabulary::label('departments', $this->department);
    }

    public function getStageLabelAttribute(): ?string
    {
        return Vocabulary::label('startup_stages', $this->stage);
    }
}
