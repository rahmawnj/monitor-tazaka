<?php

namespace App\Http\Controllers;

use App\Events\ProjectUpdated;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        return view('admin.projects.index', [
            'projects' => Project::latest()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $project = Project::create($this->validated($request));
        ProjectUpdated::dispatch($project, 'created');

        return back()->with('success', 'Project berhasil ditambahkan.');
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $project->update($this->validated($request));
        $project->refresh();
        ProjectUpdated::dispatch($project, 'updated');

        return back()->with('success', 'Project berhasil diperbarui.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();
        ProjectUpdated::dispatch($project, 'deleted');

        return back()->with('success', 'Project berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'client' => ['required', 'string', 'max:255'],
            'project_type' => ['required', 'in:tazaka_order,subcontract,external'],
            'progress' => ['required', 'integer', 'min:0', 'max:100'],
            'target_completion_date' => ['nullable', 'date'],
            'project_month' => ['nullable', 'date'],
            'description' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'lng' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        $lat = $data['lat'] ?? null;
        $lng = $data['lng'] ?? null;
        unset($data['lat'], $data['lng']);

        $data['latlong'] = ($lat !== null && $lng !== null)
            ? ['lat' => (float) $lat, 'lng' => (float) $lng]
            : null;

        return $data;
    }
}
