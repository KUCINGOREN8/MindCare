<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.14/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js" defer></script>
    <link href="{{ asset('build/output.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body class="bg-background font-sans min-h-screen flex">
    @include('components.navbar')

    @include('components.toast')

    <main class="flex flex-1 p-6 gap-6 w-full min-w-0">
        @yield('content')
    </main>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('scripts')
</body>
</html>
