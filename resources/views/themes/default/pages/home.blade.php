@extends('themes.default.layout')

@section('content')
    <section class="mx-auto max-w-6xl px-4 py-16">
        <h1 class="text-5xl font-bold leading-tight">Hi, I’m Siniša</h1>
        <p class="mt-4 text-xl text-slate-300">Full-Stack Web Developer</p>

        <div class="mt-8 flex gap-4">
            <a href="#projects" class="rounded-lg bg-sky-500 px-5 py-3 text-sm font-semibold text-slate-950">
                View my work
            </a>
            <a href="#contact" class="rounded-lg border border-white/15 px-5 py-3 text-sm font-semibold">
                Contact me
            </a>
        </div>
    </section>

    <section id="projects" class="mx-auto max-w-6xl px-4 py-12 border-t border-white/10">
        <h2 class="text-2xl font-semibold">Projects</h2>

        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse(($projects ?? collect()) as $project)
                <a href="{{ route('projects.show', $project) }}" class="rounded-2xl border border-white/10 bg-white/5 p-5">
                    @if($project->cover_image_path)
                        <img
                            src="{{ asset('storage/' . $project->cover_image_path) }}"
                            alt="{{ $project->title }}"
                            loading="lazy"
                            style="width: 100%; height: 220px; object-fit: cover;"
                            class="rounded mb-3 border"
                        >
                    @endif
                    <div class="text-xs uppercase tracking-wide text-slate-400">
                        {{ $project->client ?? 'Project' }}
                    </div>

                    <h3 class="mt-2 text-lg font-semibold">{{ $project->title }}</h3>

                    @if($project->summary)
                        <p class="mt-2 text-sm text-slate-300">{{ $project->summary }}</p>
                    @endif
                </a>
            @empty
                <p class="text-slate-300">No projects yet.</p>
            @endforelse
        </div>
    </section>
@endsection