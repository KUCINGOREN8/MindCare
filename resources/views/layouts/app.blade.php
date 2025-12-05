<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'BeOkay')</title>
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <link rel="stylesheet" href="{{ asset('build/assets/app-ccb80f30.css') }}">
    <script type="module" src="{{ asset('build/assets/app-9b71c473.js') }}"></script>
</head>

<body class="flex flex-col min-h-screen bg-white-50">
    @include('layouts.header')

    <main class="flex-grow font-Inter">
        <div class="w-full mx-auto px-0 py-0">
            @yield('content')
        </div>
    </main>

    @include('layouts.footer')

    <script src="{{ asset('js/navbar.js') }}"></script>
</body>
</html>
