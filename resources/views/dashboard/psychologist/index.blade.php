@extends('layouts.dashboard')

@section('title')
Dashboard
@endsection

@section('content')
    <div class="flex flex-1">
        <div class="flex flex-col flex-1 gap-6">
            <div class="flex flex-col bg-white p-6 gap-4 rounded-md border-grey-border border">
                <div class="flex flex-col">
                    <h1 class="text-primary font-bold text-lg">Good Day, {{ $user->full_name }}!</h1>
                    <h5 class="text-captiondark text-sm">Welcome back, here's your practice summary</h5>
                </div>
            </div>

            <x-stats-grid>
                <x-stat-card
                    title="Total Patients"
                    value="{{ $stats['total_patients'] }}"
                    subtitle="{{ $stats['new_patients_month'] }} new this month"
                    icon="assets/icons/users.svg"
                    icon-background="#DBEAFE"
                    icon-color="#2563EB"
                    trend="{{ $stats['total_patients_trend'] }}"
                />

                <x-stat-card
                    title="Sessions This Week"
                    value="{{ $stats['sessions_this_week'] }}"
                    subtitle="{{ $stats['today_appointments'] }} today, {{ $stats['completed_sessions_week'] }} completed"
                    icon="assets/icons/calendar.svg"
                    icon-background="#F3E8FF"
                    icon-color="#9333EA"
                    trend="{{ $stats['sessions_trend'] }}"
                />

                <x-stat-card
                    title="Monthly Revenue"
                    value="Rp {{ number_format($stats['monthly_revenue'], 0, ',', '.') }}"
                    subtitle="{{ now()->format('F') }} revenue"
                    icon="assets/icons/dollar.svg"
                    icon-background="#DCFCE7"
                    icon-color="#16A34A"
                    trend="{{ $stats['revenue_trend'] }}"
                />
            </x-stats-grid>

            @include('components.psychologist.upcoming-appointment')

            <div class="flex flex-col md:flex-row gap-6">
                <x-recent-clients :user="$user"/>
                <x-review-chart 
                    :labels="$stats['review_stats']['labels']"
                    :data="$stats['review_stats']['data']"
                    :colors="$stats['review_stats']['colors']"
                    :total-reviews="$stats['review_stats']['total_reviews']"
                    :average-rating="$stats['review_stats']['average_rating']"
                />
            </div>
        </div>
    </div>

    <x-user-profile-card :user="$user" />
@endsection
