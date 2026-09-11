<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name',
        'client_name',
        'started_at',
        'target_date',
        'progress',
        'status',
        'description',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'date',
            'target_date' => 'date',
            'progress' => 'integer',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'completed' => 'Completed',
            'on_hold' => 'On Hold',
            default => 'Running',
        };
    }
}
