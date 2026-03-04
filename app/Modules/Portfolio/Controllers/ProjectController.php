<?php

namespace App\Modules\Portfolio\Controllers;

use Illuminate\Routing\Controller;
use App\Modules\Portfolio\Models\Project;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::published()
            ->ordered()
            ->paginate(12)
            ->withQueryString();

        return theme_view('pages.projects.index', [
            'title' => 'Projects',
            'projects' => $projects,
            'meta' => [
                'title' => 'Projects | Siniša Nikolić',
                'description' => 'Selected projects, case studies, and work samples.',
                'canonical' => url('/projects'),
            ],
        ]);
    }

    public function show(Project $project)
    {
        if (! $project->is_published) {
            abort(404);
        }

        $canonical = url('/projects/' . $project->slug);

        // SEO description: summary -> fallback na skraćen description
        $description = $project->summary
            ?: Str::limit(trim(preg_replace('/\s+/', ' ', $project->description ?? '')), 160);

        $ogImage = $project->cover_image_path
            ? asset('storage/' . $project->cover_image_path)
            : null;

        // Prev/Next (po sort_order pa id)
        $prev = Project::published()
            ->where(function ($q) use ($project) {
                $q->where('sort_order', '<', (int)($project->sort_order ?? 0))
                  ->orWhere(function ($q2) use ($project) {
                      $q2->where('sort_order', (int)($project->sort_order ?? 0))
                         ->where('id', '<', $project->id);
                  });
            })
            ->orderByDesc('sort_order')
            ->orderByDesc('id')
            ->first();

        $next = Project::published()
            ->where(function ($q) use ($project) {
                $q->where('sort_order', '>', (int)($project->sort_order ?? 0))
                  ->orWhere(function ($q2) use ($project) {
                      $q2->where('sort_order', (int)($project->sort_order ?? 0))
                         ->where('id', '>', $project->id);
                  });
            })
            ->orderBy('sort_order')
            ->orderBy('id')
            ->first();

        // Related (isti client ako postoji, inače featured; isključi trenutni)
        $relatedQuery = Project::published()
            ->where('id', '!=', $project->id)
            ->ordered();

        if ($project->client) {
            $relatedQuery->where('client', $project->client);
        } else {
            $relatedQuery->where('is_featured', true);
        }

        $related = $relatedQuery->limit(3)->get();

        return theme_view('pages.projects.show', [
            'title' => $project->title,
            'project' => $project,
            'prev' => $prev,
            'next' => $next,
            'related' => $related,
            'meta' => [
                'title' => $project->title . ' | Projects | Siniša Nikolić',
                'description' => $description ?: 'Project details and case study.',
                'canonical' => $canonical,
                'og_image' => $ogImage,
            ],
        ]);
    }
}