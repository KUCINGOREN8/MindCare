@extends('layouts.dashboard')
@section('title', 'Psychologist Appointments')

@section('content')
<div class="flex flex-col flex-1 gap-6 min-w-0 h-full overflow-y-auto pr-2 pb-20 scroll-smooth">
    {{-- Header --}}
    <div class="flex flex-col bg-white p-6 rounded-md border-grey-border border">
        <h1 class="text-primary font-bold text-lg">My Appointments</h1>
        <h5 class="text-captiondark text-sm">Manage your client sessions and history</h5>
    </div>

    {{-- Upcoming --}}
    @include('components.psychologist.upcoming-appointment', [
        'appointments' => $upcomingAppointments,
        'showSeeAll' => false
    ])

    {{-- History --}}
    @include('components.psychologist.history-appointment', ['appointments' => $historyAppointments])

    {{-- Reschedule --}}
    @include('components.psychologist.reschedule-appointment', ['appointments' => $rescheduleAppointments])
</div>
@endsection
