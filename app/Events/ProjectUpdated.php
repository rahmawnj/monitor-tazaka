<?php

namespace App\Events;

use App\Models\Project;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProjectUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $action,
        public ?int $projectId = null,
    ) {
    }

    public function broadcastOn(): array
    {
        return [new Channel('monitor')];
    }

    public function broadcastAs(): string
    {
        return 'project.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'action' => $this->action,
            'project_id' => $this->projectId,
            'updated_at' => now()->toIso8601String(),
        ];
    }
}
