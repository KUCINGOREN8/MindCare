@extends('layouts.dashboard')
@section('title', 'Appointments')

@section('content')

    <div class="flex flex-col flex-1 gap-6 min-w-0 h-full overflow-y-auto pr-2 pb-20 scroll-smooth">
        <div class="flex flex-col bg-white p-6 rounded-md border-grey-border border">
            <h1 class="text-primary font-bold text-lg">Appointments</h1>
            <h5 class="text-captiondark text-sm">Manage your sessions and history.</h5>
        </div>

        {{-- Upcoming Appointment --}}
        @include('components.upcoming-appointment', ['showSeeAll' => false])
        
        {{-- History --}}
        @include('components.history-appointment')

        {{-- Reschedule --}}
        @include('components.reschedule-appointment')
        
    </div>
@endsection
