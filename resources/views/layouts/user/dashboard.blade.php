<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <link href="{{ asset('build/output.css') }}" rel="stylesheet">
</head>
<body class="bg-background font-sans min-h-screen flex">
    @include('sections.user.dashboard.navbar', ['navItems' => $navItems ?? []])

    {{-- Main Content --}}
    <main class="flex flex-1 p-6 gap-6 ">
        @yield('content')
    </main>

</body>
</html>
