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
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'client_name' => ['required', 'string', 'max:255'],
            'started_at' => ['nullable', 'date'],
            'target_date' => ['nullable', 'date'],
            'progress' => ['required', 'integer', 'min:0', 'max:100'],
            'status' => ['required', 'in:running,completed,on_hold'],
            'description' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);
    }
}
