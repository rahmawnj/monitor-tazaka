<?php

namespace App\Events;

use App\Models\Project;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProjectUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Project $project,
        public string $action,
    ) {}

    public function broadcastOn(): array
    {
        return [new Channel('projects')];
    }

    public function broadcastAs(): string
    {
        return 'project.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'action' => $this->action,
            'project' => [
                'id' => $this->project->id,
                'name' => $this->project->name,
                'client_name' => $this->project->client_name,
                'started_at' => $this->project->started_at?->format('Y-m-d'),
                'target_date' => $this->project->target_date?->format('Y-m-d'),
                'progress' => $this->project->progress,
                'status' => $this->project->status,
                'description' => $this->project->description,
                'notes' => $this->project->notes,
            ],
        ];
    }
}
