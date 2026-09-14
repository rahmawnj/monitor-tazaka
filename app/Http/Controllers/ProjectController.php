<?php

namespace App\Http\Controllers;

use App\Events\ProjectUpdated;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        return view('admin.projects.index', [
            'projects' => Project::orderBy('sort_order')->orderByDesc('id')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.projects.create');
    }

    public function show(Project $project): View
    {
        return view('admin.projects.show', compact('project'));
    }

    public function edit(Project $project): View
    {
        return view('admin.projects.edit', compact('project'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['progress'] = 0;
        $data['sort_order'] = ((int) Project::max('sort_order')) + 1;
        $project = Project::create($data);

        ProjectUpdated::dispatch('created', $project->id);

        return redirect()->route('admin.projects.index')->with('success', 'Project berhasil ditambahkan.');
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $data = $this->validated($request);
        $project->update($data);

        ProjectUpdated::dispatch('updated', $project->id);

        return redirect()->route('admin.projects.show', $project)->with('success', 'Project berhasil diperbarui.');
    }

    public function updateProgress(Request $request, Project $project): JsonResponse
    {
        $data = $request->validate([
            'progress' => ['required', 'integer', 'min:0', 'max:100'],
        ]);

        $project->update(['progress' => $data['progress']]);

        ProjectUpdated::dispatch('progress', $project->id);

        return response()->json([
            'success' => true,
            'progress' => $project->progress,
        ]);
    }

    public function reorder(Request $request): JsonResponse
    {
        $data = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'distinct', 'exists:projects,id'],
        ]);

        foreach ($data['ids'] as $position => $id) {
            Project::whereKey($id)->update(['sort_order' => $position]);
        }

        ProjectUpdated::dispatch('reordered');

        return response()->json(['success' => true]);
    }

    public function destroy(Project $project): RedirectResponse
    {
        $projectId = $project->id;
        $project->delete();

        ProjectUpdated::dispatch('deleted', $projectId);

        return redirect()->route('admin.projects.index')->with('success', 'Project berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'client' => ['required', 'string', 'max:255'],
            'project_type' => ['required', 'in:tazaka_order,subcontract,external'],
            'target_completion_date' => ['nullable', 'date'],
            'project_month' => ['nullable', 'regex:/^\d{4}-\d{2}$/'],
            'description' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'lng' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        if (!empty($data['project_month'])) {
            $data['project_month'] .= '-01';
        }

        $lat = $data['lat'] ?? null;
        $lng = $data['lng'] ?? null;
        unset($data['lat'], $data['lng']);

        $data['latlong'] = ($lat !== null && $lng !== null)
            ? ['lat' => (float) $lat, 'lng' => (float) $lng]
            : null;

        return $data;
    }
}
