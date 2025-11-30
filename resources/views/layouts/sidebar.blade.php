<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Be Okay</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    
    <div class="flex min-h-screen bg-gray-50">
        <aside class="w-64 h-screen sticky top-0 bg-white border-r p-6 overflow-y-auto">
            <div class="flex items-center gap-2 mb-10">
                <div class="w-7 h-7 bg-teal-500 rounded-full"></div>
                <h1 class="font-semibold text-lg">Be Okay</h1>
            </div>

            <nav class="space-y-4 text-gray-700">
                <a href="#" class="block p-2 rounded hover:bg-gray-100">Dashboard</a>
                <a href="#" class="block p-2 rounded hover:bg-gray-100">Find Psychologist</a>
                <a href="#" class="block p-2 rounded hover:bg-gray-100">Book Appointment</a>
                <a href="#" class="block p-2 rounded hover:bg-gray-100">Appointments</a>
                <a href="#" class="block p-2 rounded hover:bg-gray-100">Messages</a>
            </nav>
        </aside>

        
        @yield('content')

    </div>

</body>
</html>