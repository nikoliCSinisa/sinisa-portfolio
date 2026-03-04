<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', 'Admin')</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

        <style>
            .table td, .table th { vertical-align: middle; }
            .text-truncate-2 {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
        </style>
    </head>
    <script>
    (function () {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        function setBadgeState(btn, isOn) {
            const type = btn.dataset.type;

            btn.textContent = isOn ? 'Yes' : 'No';

            // Reset classes
            btn.classList.remove('text-bg-success', 'text-bg-primary', 'text-bg-secondary');

            if (!isOn) {
                btn.classList.add('text-bg-secondary');
                return;
            }

            if (type === 'published') btn.classList.add('text-bg-success');
            else btn.classList.add('text-bg-primary');
        }

        document.addEventListener('click', async (e) => {
            const btn = e.target.closest('.js-toggle-badge');
            if (!btn) return;

            if (btn.disabled) return;

            const enabled = btn.dataset.enabled === '1';
            if (!enabled) return;

            const url = btn.dataset.url;
            if (!url) return;

            const prevText = btn.textContent;
            btn.textContent = '...';
            btn.disabled = true;

            try {
                const res = await fetch(url, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                },
                });

                if (!res.ok) throw new Error('Request failed: ' + res.status);

                let data = null;
                const contentType = res.headers.get('content-type') || '';
                if (contentType.includes('application/json')) {
                    data = await res.json();
                }

                if (data && typeof data.value === 'boolean') {
                    setBadgeState(btn, data.value);
                } else {
                    // optimistic toggle
                    const isOn = prevText.trim().toLowerCase() !== 'yes';
                    setBadgeState(btn, isOn);
                }

                if (btn.dataset.type === 'published') {
                    const row = btn.closest('tr');
                    if (row) {
                        const featuredBtn = row.querySelector('.js-toggle-badge[data-type="featured"]');
                        if (featuredBtn) {
                            const isPublished = (btn.textContent.trim().toLowerCase() === 'yes');
                            featuredBtn.dataset.enabled = isPublished ? '1' : '0';
                            featuredBtn.disabled = !isPublished;

                            if (!isPublished) {
                                setBadgeState(featuredBtn, false);
                            }
                        }
                    }
                }

            } catch (err) {
                console.error(err);
                btn.textContent = prevText;
                alert('Toggle failed. Please try again.');
            } finally {
                btn.disabled = false;

                if (btn.dataset.type === 'featured' && btn.dataset.enabled !== '1') {
                    btn.disabled = true;
                }
            }
        });
    })();
    </script>

    <body class="bg-light">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="{{ route('admin.projects.index') }}">Admin</a>

                <div class="ms-auto d-flex gap-2">
                    <a class="btn btn-sm btn-outline-light" href="{{ route('home') }}">View site</a>

                    @auth
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn-sm btn-outline-warning" type="submit">Logout</button>
                        </form>
                    @endauth
                </div>
            </div>
        </nav>

        <main class="container py-4">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <div class="fw-semibold mb-1">Please fix the errors below.</div>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>

        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
        <script>
            const el = document.getElementById('sortable-projects');

            if (el) {

                new Sortable(el, {
                    animation: 150,

                    onEnd: function () {

                        const ids = [];

                        document.querySelectorAll('#sortable-projects tr').forEach(row => {
                            ids.push(row.dataset.id);
                        });

                        fetch('{{ route('admin.projects.reorder') }}', {
                            method: 'POST',
                            credentials: 'same-origin',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ ids })
                        });

                    }
                });

            }
        </script>
    </body>
</html>