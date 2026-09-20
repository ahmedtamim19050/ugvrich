<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IdeaSubmission extends Model
{
    protected $guarded = [];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function getDepartmentNameAttribute(): ?string
    {
        return config('rich.departments.'.$this->department);
    }

    public function getStageLabelAttribute(): ?string
    {
        return config('rich.startup_stages.'.$this->stage);
    }
}
