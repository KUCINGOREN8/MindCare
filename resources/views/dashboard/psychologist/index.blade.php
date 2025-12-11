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
                    value="247"
                    subtitle="18 new this month"
                    icon="assets/icons/users.svg"
                    icon-background="#DBEAFE"
                    icon-color="#2563EB"
                    :trend="12"
                />
                
                <x-stat-card
                    title="Session This Week"
                    value="42"
                    subtitle="6 today, 8 tomorrow"
                    icon="assets/icons/users.svg"
                    icon-background="#F3E8FF"
                    icon-color="#9333EA"
                    :trend="8"
                />
                
                <x-stat-card
                    title="Monthly Revenue"
                    value="RP 8.4 M"
                    subtitle="Keep it up"
                    icon="assets/icons/users.svg"
                    icon-background="#DCFCE7"
                    icon-color="#16A34A"
                    :trend="22"
                />
            </x-stats-grid>

            @include('components.upcoming-appointment')

            <div class="bg-white p-6 flex flex-col gap-6 rounded-md border-grey-border border">
                <div class="flex gap-4 justify-between items-start">
                    <h3 class="font-bold">Recent Clients</h3>
                    <a href="{{ route("psychologist.clients") }}" class="underline hover:text-primary text-caption text-sm ">See all</a>
                </div>
                <div class="flex flex-col gap-3">
                    @php
                        $appointments = $user->psychologist->appointments()
                            ->with('user')
                            ->orderBy('created_at', 'desc')
                            ->get();

                        $uniqueAppointments = $appointments->unique('user_id')->take(2);
                    @endphp

                    @if($uniqueAppointments->count() > 0)
                        @foreach ($uniqueAppointments as $appointment)
                            @php
                                $patient = $appointment->user;
                            @endphp

                            @if($patient)
                                <div class="bg-white p-3 flex flex-1 gap-6 rounded-md border-grey-border border">
                                    <div class="flex flex-1 justify-between">
                                        <div class="flex flex-row gap-3 items-center">
                                            <img src="{{ $patient->photo_url ? asset($patient->photo_url) : ($patient->gender=="female" ? asset('assets/icons/user_female.svg') : asset('assets/icons/user_male.svg')) }}"
                                                class="rounded-full w-12 h-12 lg:mx-0 mx-auto" alt="pfp">
                                            <p>{{ $patient->full_name }}</p>
                                        </div>
                                        <div>
                                            <div class="bg-notification-green-bg py-1 px-3 rounded-xl">
                                                <p class="text-[#16A34A]">Active</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <div class="bg-white p-6 text-center rounded-md border-grey-border border">
                            <p class="text-gray-500">No recent clients found</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <x-user-profile-card :user="$user" />
@endsection
