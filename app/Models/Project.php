<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'name', 'client', 'project_type', 'progress', 'target_completion_date',
        'project_month', 'description', 'notes', 'location', 'latlong', 'sort_order',
        'display_status',
    ];

    protected function casts(): array
    {
        return [
            'target_completion_date' => 'date',
            'project_month' => 'date',
            'progress' => 'integer',
            'latlong' => 'array',
            'sort_order' => 'integer',
        ];
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class)->orderBy('sort_order');
    }
}
