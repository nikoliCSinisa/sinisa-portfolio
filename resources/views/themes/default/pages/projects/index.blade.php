<x-app-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex items-end justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Projects</h1>
                    <p class="text-gray-600 mt-1">A selection of my work.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($projects as $project)
                    <a href="{{ route('projects.show', $project) }}"
                       class="group block overflow-hidden rounded-xl border bg-white hover:shadow-sm transition">
                        @if($project->cover_image_path)
                            <img
                                src="{{ asset('storage/' . $project->cover_image_path) }}"
                                alt="{{ $project->title }}"
                                loading="lazy"
                                class="w-full h-48 object-cover"
                            >
                        @endif

                        <div class="p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-900 group-hover:underline">
                                        {{ $project->title }}
                                    </h2>
                                    @if($project->client)
                                        <p class="text-sm text-gray-600 mt-1">{{ $project->client }}</p>
                                    @endif
                                </div>

                                @if($project->is_featured)
                                    <span class="shrink-0 inline-flex items-center rounded-full bg-blue-600 px-2.5 py-1 text-xs font-medium text-white">
                                        Featured
                                    </span>
                                @endif
                            </div>

                            @if($project->summary)
                                <p class="text-gray-600 mt-3">
                                    {{ $project->summary }}
                                </p>
                            @endif
                        </div>
                    </a>
                @empty
                    <div class="col-span-full">
                        <div class="rounded-lg border bg-white p-4 text-gray-600">
                            No published projects yet.
                        </div>
                    </div>
                @endforelse
            </div>

            @if($projects->hasPages())
                <div class="mt-8">
                    {{ $projects->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>