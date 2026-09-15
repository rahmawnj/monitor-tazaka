<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ProjectImage extends Model
{
    protected $fillable = [
        'project_id',
        'path',
        'original_name',
        'sort_order',
    ];

    protected $appends = [
        'url',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->path);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
