@php
    /** @var \App\Modules\Portfolio\Models\Project|null $project */
@endphp

@csrf

<div class="row">
    <div class="col-md-8 mb-3">
        <label class="form-label">Title *</label>
        <input
            name="title"
            class="form-control @error('title') is-invalid @enderror"
            value="{{ old('title', $project->title ?? '') }}"
            required
        >
        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Client</label>
        <input
            name="client"
            class="form-control @error('client') is-invalid @enderror"
            value="{{ old('client', $project->client ?? '') }}"
        >
        @error('client') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-8 mb-3">
        <label class="form-label">Slug (optional)</label>
        <input
            name="slug"
            class="form-control @error('slug') is-invalid @enderror"
            value="{{ old('slug', $project->slug ?? '') }}"
            placeholder="auto-generated from title if empty"
        >
        @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
        <div class="form-text">Used in URL. Example: <code>my-cool-project</code></div>
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Sort order</label>
        <input
            type="number"
            name="sort_order"
            class="form-control @error('sort_order') is-invalid @enderror"
            value="{{ old('sort_order', $project->sort_order ?? '') }}"
            placeholder="Auto (goes to end)"
            min="1"
            max="9999"
        >
        @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Summary</label>
    <textarea
        name="summary"
        rows="3"
        class="form-control @error('summary') is-invalid @enderror"
        placeholder="Short description shown in listings (max 500 chars)"
    >{{ old('summary', $project->summary ?? '') }}</textarea>
    @error('summary') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea
        name="description"
        rows="8"
        class="form-control @error('description') is-invalid @enderror"
        placeholder="Full description (can be longer text)"
    >{{ old('description', $project->description ?? '') }}</textarea>
    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Project URL</label>
        <input
            name="project_url"
            class="form-control @error('project_url') is-invalid @enderror"
            value="{{ old('project_url', $project->project_url ?? '') }}"
            placeholder="https://..."
        >
        @error('project_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Case study URL</label>
        <input
            name="case_study_url"
            class="form-control @error('case_study_url') is-invalid @enderror"
            value="{{ old('case_study_url', $project->case_study_url ?? '') }}"
            placeholder="https://..."
        >
        @error('case_study_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Cover image</label>

    @if(!empty($project?->cover_image_path))
        <div class="mb-2">
            <img
                src="{{ asset('storage/' . $project->cover_image_path) }}"
                alt=""
                style="max-width: 260px; height: auto;"
                class="rounded border"
            >
            <div class="form-text">Current: <code>{{ $project->cover_image_path }}</code></div>
        </div>
    @endif

    <input
        type="file"
        name="cover_image"
        class="form-control @error('cover_image') is-invalid @enderror"
        accept="image/*"
    >
    @error('cover_image') <div class="invalid-feedback">{{ $message }}</div> @enderror

    {{-- zadržavamo cover_image_path iz baze (nije input koji user menja) --}}
</div>

<div class="row">
    <div class="col-md-6 mb-3 d-flex align-items-end">
        <div class="form-check">
            <input
                class="form-check-input"
                type="checkbox"
                name="is_published"
                value="1"
                {{ old('is_published', $project->is_published ?? false) ? 'checked' : '' }}
            >
            <label class="form-check-label">Published</label>
        </div>
    </div>

    <div class="col-md-6 mb-3 d-flex align-items-end">
        <div class="form-check">
            <input
                class="form-check-input"
                type="checkbox"
                name="is_featured"
                value="1"
                {{ old('is_featured', $project->is_featured ?? false) ? 'checked' : '' }}
            >
            <label class="form-check-label">Featured</label>
        </div>
    </div>
</div>

<div class="d-flex gap-2">
    <button class="btn btn-primary" type="submit">Save</button>
    <a class="btn btn-outline-secondary" href="{{ route('admin.projects.index') }}">Cancel</a>
</div>