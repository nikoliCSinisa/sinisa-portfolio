@extends('layouts.admin')

@section('title', 'New project')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-0">New project</h1>
            <div class="text-muted small">Add a new portfolio entry</div>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('admin.projects.index') }}">Back</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data">
                @include('admin.projects._form')
            </form>
        </div>
    </div>
@endsection