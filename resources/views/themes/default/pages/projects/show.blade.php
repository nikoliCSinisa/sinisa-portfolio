<x-app-layout>
<div class="py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

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
                    
                    @if($project->images->count())
                        <div class="mt-10 border-t pt-8">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Gallery</h2>

                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach($project->images as $img)
                                    @php($full = asset('storage/' . $img->image_path))
                                    <button
                                        type="button"
                                        class="block group overflow-hidden rounded-lg border bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        data-lightbox="project"
                                        data-src="{{ $full }}"
                                        data-alt="{{ $project->title }}"
                                    >
                                        <img
                                            src="{{ $full }}"
                                            alt="{{ $project->title }}"
                                            loading="lazy"
                                            class="w-full h-44 object-cover transition-transform duration-200 group-hover:scale-[1.02]"
                                        >
                                    </button>
                                @endforeach
                            </div>
                        </div>
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
</div>

{{-- Lightbox --}}
<div id="lightbox"
     class="fixed inset-0 z-50 hidden"
     aria-hidden="true">
    <div class="absolute inset-0 bg-black/80" data-lb-close></div>

    <div class="relative h-full w-full flex items-center justify-center p-4">
        <button type="button"
                class="absolute top-4 right-4 rounded-full bg-white/10 text-white px-3 py-2 hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white"
                aria-label="Close"
                data-lb-close>
            ✕
        </button>

        <button type="button"
                class="absolute left-3 md:left-6 rounded-full bg-white/10 text-white px-3 py-2 hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white"
                aria-label="Previous image"
                data-lb-prev>
            ‹
        </button>

        <figure class="max-w-6xl w-full">
            <img id="lightboxImg"
                 src=""
                 alt=""
                 class="... select-none"
                 draggable="false"
                 class="max-h-[80vh] w-full object-contain rounded-lg shadow-xl bg-black">
            <figcaption id="lightboxCaption"
                        class="mt-3 text-center text-sm text-white/80"></figcaption>
        </figure>

        <button type="button"
                class="absolute right-3 md:right-6 rounded-full bg-white/10 text-white px-3 py-2 hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white"
                aria-label="Next image"
                data-lb-next>
            ›
        </button>
    </div>
</div>

<script>
(() => {
    const triggers = Array.from(document.querySelectorAll('[data-lightbox="project"]'));
    const lb = document.getElementById('lightbox');
    if (!triggers.length || !lb) return;

    const img = document.getElementById('lightboxImg');
    const caption = document.getElementById('lightboxCaption');

    const closeEls = Array.from(lb.querySelectorAll('[data-lb-close]'));
    const prevBtn = lb.querySelector('[data-lb-prev]');
    const nextBtn = lb.querySelector('[data-lb-next]');

    let index = 0;
    let lastActiveEl = null;

    function setOpen(isOpen) {
        lb.classList.toggle('hidden', !isOpen);
        lb.setAttribute('aria-hidden', String(!isOpen));
        document.documentElement.classList.toggle('overflow-hidden', isOpen);
    }

    function render(i) {
        index = (i + triggers.length) % triggers.length;

        const el = triggers[index];
        const src = el.getAttribute('data-src');
        const alt = el.getAttribute('data-alt') || '';
        img.src = src;
        img.alt = alt;

        // Caption: možeš kasnije ubaciti npr. "3 / 10"
        caption.textContent = `${index + 1} / ${triggers.length}`;
    }

    function openAt(i) {
        lastActiveEl = document.activeElement;
        setOpen(true);
        render(i);
        // focus close dugme radi a11y
        closeEls[0]?.focus();
    }

    function close() {
        setOpen(false);
        img.src = '';
        caption.textContent = '';
        if (lastActiveEl && typeof lastActiveEl.focus === 'function') {
            lastActiveEl.focus();
        }
    }

    function next() { render(index + 1); }
    function prev() { render(index - 1); }

    triggers.forEach((el, i) => {
        el.addEventListener('click', () => openAt(i));
        el.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                openAt(i);
            }
        });
    });

    closeEls.forEach(el => el.addEventListener('click', close));
    nextBtn?.addEventListener('click', next);
    prevBtn?.addEventListener('click', prev);

    // Keyboard controls
    document.addEventListener('keydown', (e) => {
        // Touch swipe (mobile)
        let startX = 0;
        let startY = 0;
        let isTouching = false;

        function onTouchStart(e) {
            if (lb.classList.contains('hidden')) return;
            if (!e.touches || e.touches.length !== 1) return;

            const t = e.touches[0];
            startX = t.clientX;
            startY = t.clientY;
            isTouching = true;
        }

        function onTouchMove(e) {
            // ne blokiramo scroll dok ne znamo da je horizontalni swipe
            if (!isTouching || lb.classList.contains('hidden')) return;
        }

        function onTouchEnd(e) {
            if (!isTouching || lb.classList.contains('hidden')) return;
            isTouching = false;

            const t = (e.changedTouches && e.changedTouches[0]) ? e.changedTouches[0] : null;
            if (!t) return;

            const dx = t.clientX - startX;
            const dy = t.clientY - startY;

            // ignore ako je više vertikalno nego horizontalno (da ne ubijemo scroll intent)
            if (Math.abs(dx) < 40 || Math.abs(dx) < Math.abs(dy)) return;

            if (dx < 0) next();   // swipe left -> next
            else prev();          // swipe right -> prev
        }

        // Bind on the whole lightbox (overlay + image area)
        lb.addEventListener('touchstart', onTouchStart, { passive: true });
        lb.addEventListener('touchmove', onTouchMove, { passive: true });
        lb.addEventListener('touchend', onTouchEnd, { passive: true });

        if (lb.classList.contains('hidden')) return;

        if (e.key === 'Escape') close();
        if (e.key === 'ArrowRight') next();
        if (e.key === 'ArrowLeft') prev();
    });

    // Click outside image closes (overlay already handles via data-lb-close)
})();
</script>
</x-app-layout>