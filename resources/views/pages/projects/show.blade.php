@extends('layouts.app')

@section('content')
<div class="container py-5">

    <div class="mb-4">
        <a href="{{ route('projects.index') }}" class="text-decoration-none">&larr; Back to Projects</a>
    </div>

    <div class="row g-4 align-items-start">
        <div class="col-12 col-lg-7">
            @if($project->cover_image_path)
                <img
                    src="{{ asset('storage/' . $project->cover_image_path) }}"
                    alt="{{ $project->title }}"
                    class="img-fluid rounded border"
                    loading="eager"
                >
            @endif
        </div>

        <div class="col-12 col-lg-5">
            <h1 class="mb-2">{{ $project->title }}</h1>

            @if($project->client)
                <div class="text-muted mb-3">{{ $project->client }}</div>
            @endif

            @if($project->summary)
                <p class="lead">{{ $project->summary }}</p>
            @endif

            <div class="d-flex flex-wrap gap-2 my-3">
                @if($project->project_url)
                    <a class="btn btn-primary" href="{{ $project->project_url }}" target="_blank" rel="noopener">
                        Visit Project
                    </a>
                @endif

                @if($project->case_study_url)
                    <a class="btn btn-outline-secondary" href="{{ $project->case_study_url }}" target="_blank" rel="noopener">
                        Case Study
                    </a>
                @endif
            </div>

            @if($project->description)
                <div class="mt-4">
                    <h2 class="h5 mb-2">About</h2>
                    <div class="text-muted">
                        {!! nl2br(e($project->description)) !!}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <hr class="my-5">

    <div class="d-flex justify-content-between gap-3">
        <div>
            @if($prev)
                <a href="{{ route('projects.show', $prev) }}" class="text-decoration-none">
                    &larr; {{ $prev->title }}
                </a>
            @endif
        </div>

        <div class="text-end">
            @if($next)
                <a href="{{ route('projects.show', $next) }}" class="text-decoration-none">
                    {{ $next->title }} &rarr;
                </a>
            @endif
        </div>
    </div>

    @if($related->count())
        <hr class="my-5">

        <h2 class="h4 mb-3">Related projects</h2>

        <div class="row g-4">
            @foreach($related as $item)
                <div class="col-12 col-md-6 col-lg-4">
                    <a href="{{ route('projects.show', $item) }}" class="text-decoration-none text-reset">
                        <div class="card h-100">
                            @if($item->cover_image_path)
                                <img
                                    src="{{ asset('storage/' . $item->cover_image_path) }}"
                                    alt="{{ $item->title }}"
                                    loading="lazy"
                                    style="height: 180px; width: 100%; object-fit: cover;"
                                    class="card-img-top"
                                >
                            @endif
                            <div class="card-body">
                                <div class="fw-semibold">{{ $item->title }}</div>
                                @if($item->client)
                                    <div class="text-muted small">{{ $item->client }}</div>
                                @endif
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection