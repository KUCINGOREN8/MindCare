<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="{{ asset('build/output.css') }}" rel="stylesheet">
</head>
<body class="bg-background font-sans min-h-screen flex">
    @include('components.navbar')

    <main class="flex flex-1 p-6 gap-6 w-full min-w-0">
        @yield('content')
    </main>
    @include('components.toast')

    <main class="flex flex-1 p-6 gap-6 w-full min-w-0">
        @yield('content')
    </main>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>