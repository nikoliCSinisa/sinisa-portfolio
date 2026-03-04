@extends('layouts.admin')

@section('title', 'Edit project')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-0">Edit project</h1>
            <div class="text-muted small">{{ $project->title }}</div>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('admin.projects.index') }}">Back</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.projects.update', $project) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('admin.projects._form', ['project' => $project])
            </form>
        </div>
    </div>
@endsection