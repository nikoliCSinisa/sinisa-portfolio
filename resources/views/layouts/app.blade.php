<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO / Title --}}
    <title>{{ $meta['title'] ?? ($title ?? config('app.name', 'Laravel')) }}</title>

    @if(!empty($meta['description']))
        <meta name="description" content="{{ $meta['description'] }}">
    @endif

    @if(!empty($meta['canonical']))
        <link rel="canonical" href="{{ $meta['canonical'] }}">
    @endif

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $meta['title'] ?? ($title ?? config('app.name', 'Laravel')) }}">

    @if(!empty($meta['description']))
        <meta property="og:description" content="{{ $meta['description'] }}">
    @endif

    @if(!empty($meta['canonical']))
        <meta property="og:url" content="{{ $meta['canonical'] }}">
    @endif

    @if(!empty($meta['og_image']))
        <meta property="og:image" content="{{ $meta['og_image'] }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
