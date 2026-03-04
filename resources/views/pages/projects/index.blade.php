@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h1 class="mb-1">Projects</h1>
            <div class="text-muted">A selection of my work.</div>
        </div>
    </div>

    <div class="row g-4">
        @forelse($projects as $project)
            <div class="col-12 col-md-6 col-lg-4">
                <a href="{{ route('projects.show', $project) }}" class="text-decoration-none text-reset">
                    <div class="card h-100">
                        @if($project->cover_image_path)
                            <img
                                src="{{ asset('storage/' . $project->cover_image_path) }}"
                                alt="{{ $project->title }}"
                                loading="lazy"
                                style="height: 220px; width: 100%; object-fit: cover;"
                                class="card-img-top"
                            >
                        @endif

                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <h2 class="h5 mb-1">{{ $project->title }}</h2>
                                @if($project->is_featured)
                                    <span class="badge text-bg-primary">Featured</span>
                                @endif
                            </div>

                            @if($project->client)
                                <div class="text-muted small mb-2">{{ $project->client }}</div>
                            @endif

                            @if($project->summary)
                                <p class="mb-0 text-muted">{{ $project->summary }}</p>
                            @endif
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-light border">No published projects yet.</div>
            </div>
        @endforelse
    </div>

    @if($projects->hasPages())
        <div class="mt-4">
            {{ $projects->links() }}
        </div>
    @endif
</div>
@endsection