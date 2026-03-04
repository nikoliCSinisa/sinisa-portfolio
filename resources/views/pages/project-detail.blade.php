@extends('layouts.app')

@section('content')
<div class="container py-5">

    <h1 class="mb-3">{{ $project->title }}</h1>

    @if($project->cover_image_path)
        <img
            src="{{ asset('storage/' . $project->cover_image_path) }}"
            alt="{{ $project->title }}"
            class="img-fluid mb-4 rounded"
        >
    @endif

    @if($project->client)
        <p><strong>Client:</strong> {{ $project->client }}</p>
    @endif

    @if($project->project_url)
        <p>
            <a href="{{ $project->project_url }}" target="_blank">
                Visit Project
            </a>
        </p>
    @endif

    @if($project->case_study_url)
        <p>
            <a href="{{ $project->case_study_url }}" target="_blank">
                View Case Study
            </a>
        </p>
    @endif

    <div class="mt-4">
        {!! nl2br(e($project->description)) !!}
    </div>

</div>
@endsection