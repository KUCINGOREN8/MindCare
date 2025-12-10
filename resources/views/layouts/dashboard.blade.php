<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link href="{{ asset('css/output.css') }}" rel="stylesheet">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    @stack('styles')
</head>
<body class="bg-background font-sans min-h-screen flex">
    <x-navbar :role="auth()->user()->role" />

    @include('components.snackbar')

    <main class="flex flex-1 p-6 gap-6 w-full min-w-0">
        @yield('content')
    </main>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        window.showSnackbar = function(message, type = 'success') {
            window.dispatchEvent(new CustomEvent('open-snackbar', {
                detail: { message, type }
            }));
        };
        
        window.hideSnackbar = function() {
            window.dispatchEvent(new CustomEvent('close-snackbar'));
        };
    </script>
    @stack('scripts')
</body>
</html>
