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
        <div class="flex flex-1">
            <div class="flex flex-col flex-1 gap-6">
            <div class="bg-white p-6 rounded-md border-grey-border border">
                <h1 class="text-primary font-bold">Good Day, Jane Cooper!</h1>
                <h5 class="text-captiondark">How are you feeling today?</h5>
            </div>

            <div class="bg-white p-6 flex flex-col gap-6 rounded-md border-grey-border border">
                <div class="flex flex-1 gap-4 justify-between">
                    <h3 class="font-bold">Upcoming Appointments</h1>
                    <a href="appointment-history.blade.php" class="underline text-caption">See all</a>
                </div>
                <x-session-card 
                    name="Dr. Emily Chen" 
                    specialization="Clinical Psychologist" 
                    time="12.00 PM" 
                    joinRoute="#"
                    rescheduleRoute="#"
                />

                <x-session-card 
                    name="Dr. Michael Tan" 
                    specialization="Child Therapist" 
                    time="3.30 PM"
                    joinRoute="#"
                    rescheduleRoute="#"
                />
            </div>
        </div>
        </div>
        
        <div class="w-1/5 flex flex-col p-6 gap-6 bg-white rounded-md border-grey-border border">
            <h4>Jane Cooper</h4>
            
        </div>
    </main>

</body>
</html>
