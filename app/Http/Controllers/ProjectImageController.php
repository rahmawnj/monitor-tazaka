<?php

namespace App\Http\Controllers;

use App\Events\ProjectUpdated;
use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectImageController extends Controller
{
    public function store(Request $request, Project $project): RedirectResponse
    {
        $request->validate([
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $nextOrder = ((int) $project->images()->max('sort_order')) + 1;

        foreach ($request->file('images', []) as $image) {
            $path = $image->store("projects/{$project->id}", 'public');
            $project->images()->create([
                'path' => $path,
                'original_name' => $image->getClientOriginalName(),
                'sort_order' => $nextOrder++,
            ]);
        }

        ProjectUpdated::dispatch('images', $project->id);

        return back()->with('success', 'Image project berhasil ditambahkan.');
    }

    public function destroy(ProjectImage $projectImage): RedirectResponse
    {
        $projectId = $projectImage->project_id;
        Storage::disk('public')->delete($projectImage->path);
        $projectImage->delete();
        $this->normalize($projectId);
        ProjectUpdated::dispatch('images', $projectId);

        return back()->with('success', 'Image berhasil dihapus.');
    }

    public function reorder(Request $request, Project $project): JsonResponse
    {
        $data = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'distinct'],
        ]);

        $validIds = $project->images()->whereIn('id', $data['ids'])->pluck('id')->all();
        if (count($validIds) !== count($data['ids'])) {
            return response()->json(['message' => 'Urutan image tidak valid.'], 422);
        }

        foreach ($data['ids'] as $position => $id) {
            $project->images()->whereKey($id)->update(['sort_order' => $position]);
        }

        ProjectUpdated::dispatch('images', $project->id);
        return response()->json(['success' => true]);
    }

    private function normalize(int $projectId): void
    {
        $images = ProjectImage::where('project_id', $projectId)->orderBy('sort_order')->orderBy('id')->get();
        foreach ($images as $position => $image) {
            $image->update(['sort_order' => $position]);
        }
    }
}
