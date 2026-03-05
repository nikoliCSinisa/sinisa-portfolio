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

            <hr class="my-4">

            <h2 class="h5 mb-3">Gallery images</h2>

            <form action="{{ route('admin.projects.images.store', $project) }}"
                method="POST"
                enctype="multipart/form-data"
                class="mb-4">
                @csrf

                <div class="mb-2">
                    <input type="file"
                        name="images[]"
                        class="form-control @error('images') is-invalid @enderror"
                        accept="image/*"
                        multiple>
                    @error('images') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    @error('images.*') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    <div class="form-text">You can select multiple images. Max 5MB each (jpg/png/webp).</div>
                </div>

                <button class="btn btn-outline-primary" type="submit">Upload</button>
            </form>

            @php
                $images = $project->images()->ordered()->get();
            @endphp

            @if($images->count())
                <div class="row g-3" id="sortable-project-images">
                    @foreach($images as $img)
                        <div class="col-6 col-md-4 col-lg-3" data-id="{{ $img->id }}">
                            <div class="card shadow-sm">
                                <img
                                    src="{{ asset('storage/' . $img->image_path) }}"
                                    alt=""
                                    style="width:100%; height: 160px; object-fit: cover;"
                                    class="rounded-top"
                                >
                                <div class="card-body d-flex justify-content-between align-items-center py-2">
                                    <small class="text-muted">#{{ $img->sort_order }}</small>

                                    <form action="{{ route('admin.projects.images.destroy', $img) }}"
                                        method="POST"
                                        onsubmit="return confirm('Delete this image?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="form-text mt-2">Drag & drop images to reorder.</div>
            @else
                <div class="text-muted">No gallery images yet.</div>
            @endif
            
        </div>
    </div>
@endsection