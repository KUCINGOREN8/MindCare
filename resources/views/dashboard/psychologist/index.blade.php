@extends('layouts.dashboard')

@section('title')
    Dashboard
@endsection

@section('content')
    <div class="flex flex-col lg:flex-row flex-1 gap-6">
        <div class="flex flex-col flex-1 gap-6 min-w-0">
            {{-- Header --}}
            <div class="flex flex-col bg-white p-6 gap-4 rounded-md border-grey-border border">
                <div class="flex flex-col">
                    {{-- Translate Greeting dengan Parameter Nama --}}
                    <h1 class="text-primary font-bold text-lg">
                        {{ __('psychologist_dashboard.greeting', ['name' => $user->full_name]) }}
                    </h1>
                    <h5 class="text-captiondark text-sm">
                        {{ __('psychologist_dashboard.welcome_summary') }}
                    </h5>
                </div>
            </div>

            <x-stats-grid>
                {{-- Card 1: Patients --}}
                <x-stat-card title="{{ __('psychologist_dashboard.stat_patients') }}" value="{{ $stats['total_patients'] }}"
                    subtitle="{{ $stats['new_patients_month'] }} {{ __('psychologist_dashboard.patients_new') }}"
                    icon="assets/icons/users.svg" icon-background="#DBEAFE" icon-color="#2563EB"
                    trend="{{ $stats['total_patients_trend'] }}" />

                {{-- Card 2: Sessions --}}
                <x-stat-card title="{{ __('psychologist_dashboard.stat_sessions') }}"
                    value="{{ $stats['sessions_this_week'] }}"
                    subtitle="{{ $stats['today_appointments'] }} {{ __('psychologist_dashboard.sessions_today') }}, {{ $stats['completed_sessions_week'] }} {{ __('psychologist_dashboard.sessions_completed') }}"
                    icon="assets/icons/calendar.svg" icon-background="#F3E8FF" icon-color="#9333EA"
                    trend="{{ $stats['sessions_trend'] }}" />

                {{-- Card 3: Revenue --}}
                <x-stat-card title="{{ __('psychologist_dashboard.stat_revenue') }}"
                    value="Rp {{ number_format($stats['monthly_revenue'], 0, ',', '.') }}" {{-- Gunakan translatedFormat('F') agar nama bulan (Januari) ikut ter-translate --}}
                    subtitle="{{ __('psychologist_dashboard.revenue_label') }} {{ now()->translatedFormat('F') }}"
                    icon="assets/icons/dollar.svg" icon-background="#DCFCE7" icon-color="#16A34A"
                    trend="{{ $stats['revenue_trend'] }}" />
            </x-stats-grid>

            @include('components.psychologist.upcoming-appointment')

            <div class="flex flex-col md:flex-row gap-6">
                <x-recent-clients :user="$user" />
                <x-review-chart :labels="$stats['review_stats']['labels']" :data="$stats['review_stats']['data']" :colors="$stats['review_stats']['colors']" :total-reviews="$stats['review_stats']['total_reviews']" :average-rating="$stats['review_stats']['average_rating']" />
            </div>
        </div>

       <div class="w-full lg:w-auto lg:shrink-0 self-start">
            <x-user-profile-card :user="$user" :notifications="$notifications"/>
        </div>

    </div>
@endsection
