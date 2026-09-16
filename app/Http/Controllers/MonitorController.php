<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class MonitorController extends Controller
{
    public function index(): View
    {
        $projects = $this->monitorProjects();

        return view('monitor', compact('projects'));
    }

    public function data(): JsonResponse
    {
        $projects = $this->monitorProjects();

        return response()->json([
            'projects' => $projects,
            'summary' => [
                'total' => $projects->count(),
                'running' => $projects->where('progress', '<', 100)->count(),
                'completed' => $projects->where('progress', '>=', 100)->count(),
                'average_progress' => (int) round($projects->avg('progress') ?? 0),
            ],
        ]);
    }

    private function monitorProjects()
    {
        $projects = Project::with('images')
            ->where('display_status', 'visible')
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        // Map monitor expects [lat, lng], while the admin form stores {lat, lng}.
        $projects->each(function (Project $project) {
            $coords = $project->latlong;
            if (is_array($coords) && array_key_exists('lat', $coords) && array_key_exists('lng', $coords)) {
                $project->setAttribute('latlong', [(float) $coords['lat'], (float) $coords['lng']]);
            }
        });

        return $projects;
    }
}
