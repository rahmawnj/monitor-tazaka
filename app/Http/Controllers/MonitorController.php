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
            'projects' => Project::latest()->get(),
        ]);
    }

    public function data(): JsonResponse
    {
        return response()->json([
            'projects' => Project::latest()->get(),
            'summary' => [
                'total' => Project::count(),
                'running' => Project::where('status', 'running')->count(),
                'completed' => Project::where('status', 'completed')->count(),
                'on_hold' => Project::where('status', 'on_hold')->count(),
                'average_progress' => (int) round(Project::avg('progress') ?? 0),
            ],
        ]);
    }
}
