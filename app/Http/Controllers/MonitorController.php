<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class MonitorController extends Controller
{
    public function index(): View
    {
        return view('monitor', [
            'projects' => Project::orderBy('sort_order')->orderByDesc('id')->get(),
        ]);
    }

    public function data(): JsonResponse
    {
        $projects = Project::orderBy('sort_order')->orderByDesc('id')->get();

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
}
