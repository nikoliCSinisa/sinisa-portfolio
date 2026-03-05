<?php

namespace App\Modules\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Portfolio\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Modules\Portfolio\Models\ProjectImage;
use Illuminate\Support\Facades\Storage;


class AdminProjectController extends Controller
{
    public function index()
    {
        $projects = Project::query()
            ->orderBy('sort_order')
            ->paginate(15);

        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        return view('admin.projects.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        if (empty($data['sort_order'])) {
            $data['sort_order'] = (Project::max('sort_order') ?? 0) + 1;
        }

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data['slug'] = $this->uniqueSlug($data['slug']);

        $data['is_featured'] = (bool) ($data['is_featured'] ?? false);
        $data['is_published'] = (bool) ($data['is_published'] ?? false);

        // Upload cover image
        if ($request->hasFile('cover_image')) {
            $data['cover_image_path'] = $request->file('cover_image')->store('projects', 'public');
        }

        Project::create($data);

        return redirect()
            ->route('admin.projects.index')
            ->with('success', 'Project created.');
    }

    public function edit(Project $project)
    {
        return view('admin.projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $data = $this->validated($request, $project->id);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data['slug'] = $this->uniqueSlug($data['slug'], $project->id);

        $data['is_featured'] = (bool) ($data['is_featured'] ?? false);
        $data['is_published'] = (bool) ($data['is_published'] ?? false);

        // Upload new cover image (delete old)
        if ($request->hasFile('cover_image')) {
            if ($project->cover_image_path && Storage::disk('public')->exists($project->cover_image_path)) {
                Storage::disk('public')->delete($project->cover_image_path);
            }

            $data['cover_image_path'] = $request->file('cover_image')->store('projects', 'public');
        }

        $project->update($data);

        return redirect()
            ->route('admin.projects.index')
            ->with('success', 'Project updated.');
    }

    public function destroy(Project $project)
    {
        if ($project->cover_image_path && Storage::disk('public')->exists($project->cover_image_path)) {
            Storage::disk('public')->delete($project->cover_image_path);
        }

        $project->delete();

        return redirect()
            ->route('admin.projects.index')
            ->with('success', 'Project deleted.');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        $slugUniqueRule = 'unique:projects,slug';
        if ($ignoreId) {
            $slugUniqueRule .= ',' . $ignoreId;
        }

        return $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'client' => ['nullable', 'string', 'max:160'],
            'slug' => ['nullable', 'string', 'max:190', $slugUniqueRule],
            'summary' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'cover_image_path' => ['nullable', 'string', 'max:255'],
            'project_url' => ['nullable', 'url', 'max:255'],
            'case_study_url' => ['nullable', 'url', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:1', 'max:9999'],
            'is_featured' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'], // 5MB
        ]);
    }

    private function uniqueSlug(string $baseSlug, ?int $ignoreId = null): string
    {
        $slug = $baseSlug;
        $i = 2;

        while (
            Project::query()
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $i;
            $i++;
        }

        return $slug;
    }

    public function togglePublished(Project $project)
    {
        $project->is_published = ! (bool) $project->is_published;

        if (! $project->is_published) {
            $project->is_featured = false;
        }

        $project->save();

        if (request()->expectsJson()) {
            return response()->json([
                'value' => (bool) $project->is_published,
                'is_featured' => (bool) $project->is_featured,
            ]);
        }

        return redirect()->route('admin.projects.index')->with('success', 'Publish status updated.');
    }

    public function toggleFeatured(Project $project)
    {
        if (! $project->is_published) {
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Publish first.'], 422);
            }

            return redirect()->route('admin.projects.index')->with('success', 'Publish the project before featuring it.');
        }

        $project->is_featured = ! (bool) $project->is_featured;
        $project->save();

        if (request()->expectsJson()) {
            return response()->json([
                'value' => (bool) $project->is_featured,
            ]);
        }

        return redirect()->route('admin.projects.index')->with('success', 'Featured status updated.');
    }

    public function reorder(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $index => $id) {
            Project::where('id', $id)->update([
                'sort_order' => $index + 1
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function imagesStore(Request $request, Project $project)
    {
        $request->validate([
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $nextOrder = (int) ($project->images()->max('sort_order') ?? 0) + 1;

        foreach ($request->file('images') as $file) {
            $path = $file->store('projects/gallery', 'public');

            $project->images()->create([
                'image_path' => $path,
                'sort_order' => $nextOrder,
            ]);

            $nextOrder++;
        }

        return redirect()
            ->route('admin.projects.edit', $project)
            ->with('success', 'Images uploaded.');
    }

    public function imagesDestroy(ProjectImage $image)
    {
        $projectId = $image->project_id;

        if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }

        $image->delete();

        return redirect()
            ->route('admin.projects.edit', $projectId)
            ->with('success', 'Image deleted.');
    }

    public function imagesReorder(Request $request, Project $project)
    {
        $ids = $request->input('ids');

        if (!is_array($ids) || count($ids) === 0) {
            return response()->json(['message' => 'Invalid ids'], 422);
        }

        // sigurnost: reorder samo slika koje pripadaju projektu
        $allowed = $project->images()->whereIn('id', $ids)->pluck('id')->all();

        $pos = 1;
        foreach ($ids as $id) {
            if (!in_array((int)$id, $allowed, true) && !in_array($id, $allowed, true)) {
                continue;
            }

            ProjectImage::where('id', $id)->update(['sort_order' => $pos]);
            $pos++;
        }

        return response()->json(['success' => true]);
    }
}