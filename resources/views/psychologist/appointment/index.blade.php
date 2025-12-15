@extends('layouts.dashboard')
{{-- TRANSLATE TAB TITLE --}}
@section('title', __('psychologist_appointment.page_tab_title'))

@section('content')
    <div class="flex flex-col flex-1 gap-6 min-w-0 h-full overflow-y-auto pr-2 pb-20 scroll-smooth">
        {{-- Header --}}
        <div class="flex flex-col bg-white p-6 rounded-md border-grey-border border">
            {{-- TRANSLATE HEADER & SUBTITLE --}}
            <h1 class="text-primary font-bold text-lg">{{ __('psychologist_appointment.page_header') }}</h1>
            <h5 class="text-captiondark text-sm">{{ __('psychologist_appointment.page_subtitle') }}</h5>
        </div>

        {{-- Upcoming --}}
        {{-- Komponen ini sudah kita translate sebelumnya di psychologist_dashboard.php --}}
        @include('components.psychologist.upcoming-appointment', [
            'appointments' => $upcomingAppointments,
            'showSeeAll' => false,
        ])

        {{-- History --}}
        {{-- Komponen ini sudah kita translate sebelumnya di psychologist_dashboard.php --}}
        @include('components.psychologist.history-appointment', ['appointments' => $historyAppointments])

        {{-- Reschedule (Commented out in original code)
    @include('components.psychologist.reschedule-appointment', ['appointments' => $rescheduleAppointments]) --}}
    </div>
@endsection
