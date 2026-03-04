@extends('layouts.admin')

@section('title', 'Projects')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-0">Projects</h1>
            <div class="text-muted small">Manage portfolio projects</div>
        </div>

        <a class="btn btn-primary" href="{{ route('admin.projects.create') }}">New project</a>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 70px;">Order</th>
                        <th>Title</th>
                        <th style="width: 170px;">Client</th>
                        <th style="width: 120px;">Published</th>
                        <th style="width: 120px;">Featured</th>
                        <th style="width: 220px;" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody id="sortable-projects">
                    @forelse($projects as $project)
                        <tr data-id="{{ $project->id }}">
                            <td>
                                <span class="badge text-bg-secondary">{{ $project->sort_order }}</span>
                            </td>

                            <td>
                                <div class="fw-semibold">
                                    @if($project->cover_image_path)
                                        <img
                                            src="{{ asset('storage/' . $project->cover_image_path) }}"
                                            alt="{{ $project->title }}"
                                            style="width: 64px; height: 40px; object-fit: cover;"
                                            class="rounded border me-2"
                                        >
                                    @endif
                                    {{ $project->title }}
                                </div>
                                <div class="text-muted small">
                                    <span class="me-2">/{{ $project->slug }}</span>
                                    @if($project->project_url)
                                        <span class="me-2">•</span>
                                        <a href="{{ $project->project_url }}" target="_blank" rel="noopener">Project</a>
                                    @endif
                                    @if($project->case_study_url)
                                        <span class="me-2">•</span>
                                        <a href="{{ $project->case_study_url }}" target="_blank" rel="noopener">Case study</a>
                                    @endif
                                </div>
                                @if($project->summary)
                                    <div class="text-muted small text-truncate-2 mt-1">{{ $project->summary }}</div>
                                @endif
                            </td>

                            <td class="text-muted">
                                {{ $project->client ?: '—' }}
                            </td>

                            <td>
                                <button
                                    type="button"
                                    class="badge border-0 js-toggle-badge {{ $project->is_published ? 'text-bg-success' : 'text-bg-secondary' }}"
                                    data-url="{{ route('admin.projects.togglePublished', $project) }}"
                                    data-type="published"
                                    data-enabled="1"
                                    style="cursor:pointer"
                                    title="Toggle published"
                                >
                                    {{ $project->is_published ? 'Yes' : 'No' }}
                                </button>
                            </td>

                            <td>
                                <button
                                    type="button"
                                    class="badge border-0 js-toggle-badge {{ $project->is_featured ? 'text-bg-primary' : 'text-bg-secondary' }}"
                                    data-url="{{ route('admin.projects.toggleFeatured', $project) }}"
                                    data-type="featured"
                                    data-enabled="{{ $project->is_published ? '1' : '0' }}"
                                    style="cursor:pointer"
                                    title="{{ $project->is_published ? 'Toggle featured' : 'Publish first' }}"
                                    {{ $project->is_published ? '' : 'disabled' }}
                                >
                                    {{ $project->is_featured ? 'Yes' : 'No' }}
                                </button>
                            </td>

                            <td class="text-end">
                                @if($project->is_published)
                                    <a class="btn btn-sm btn-outline-primary"
                                    href="{{ route('projects.show', $project) }}"
                                    target="_blank" rel="noopener">
                                        View
                                    </a>
                                @else
                                    <button class="btn btn-sm btn-outline-primary" type="button" disabled title="Publish first">
                                        View
                                    </button>
                                @endif

                                <a class="btn btn-sm btn-outline-secondary"
                                href="{{ route('admin.projects.edit', $project) }}">
                                    Edit
                                </a>

                                <form class="d-inline"
                                    action="{{ route('admin.projects.destroy', $project) }}"
                                    method="POST"
                                    onsubmit="return confirm('Delete this project?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                No projects yet.
                                <a href="{{ route('admin.projects.create') }}">Create your first one</a>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($projects->hasPages())
            <div class="card-body border-top">
                {{ $projects->links() }}
            </div>
        @endif
    </div>
@endsection