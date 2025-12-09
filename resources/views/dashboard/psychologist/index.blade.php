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
            
            <div class="flex flex-col md:flex-row gap-6">
                <div class="flex flex-1 flex-col bg-white p-6 gap-6 rounded-md border-grey-border border">
                    <div class="flex gap-4 justify-between items-start">
                        <div class="bg-[#DBEAFE] p-[10px] rounded-md">
                            {!! str_replace(
                                '<svg ',
                                '<svg class="text-[#2563EB]" fill="currentColor" ',
                                file_get_contents(public_path( 'assets/icons/users.svg' ))
                                ) !!}
                        </div>
                        
                        <div class="flex flex-row gap-1 items-center">
                            <img src="{{ asset('assets/icons/arrow-up.svg') }}" class="mt-[3px]" alt="">
                            <p class="text-[#16A34A]">12%</p>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <p class="text-caption">Total Patients</p>
                        <h1 class='font-bold text-2xl'>247</h1>
                        <p class="text-caption">18 new this month</p>
                    </div>
                </div>
                <div class="bg-white p-6 flex flex-1 flex-col gap-6 rounded-md border-grey-border border">
                    <div class="flex gap-4 justify-between items-start">
                        <div class="bg-[#F3E8FF] p-[10px] rounded-md">
                            {!! str_replace(
                                '<svg ',
                                '<svg class="text-[#9333EA]" fill="currentColor" ',
                                file_get_contents(public_path( 'assets/icons/users.svg' ))
                                ) !!}
                        </div>
                        
                        <div class="flex flex-row gap-1 items-center">
                            <img src="{{ asset('assets/icons/arrow-up.svg') }}" class="mt-[3px]" alt="">
                            <p class="text-[#16A34A]">8%</p>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <p class="text-caption">Session This Week</p>
                        <h1 class='font-bold text-2xl'>42</h1>
                        <p class="text-caption">6 today, 8 tomorrow</p>
                    </div>
                </div>
                <div class="bg-white p-6 flex flex-1 flex-col gap-6 rounded-md border-grey-border border">
                    <div class="flex gap-4 justify-between items-start">
                        <div class="bg-[#DCFCE7] p-[10px] rounded-md">
                            {!! str_replace(
                                '<svg ',
                                '<svg class="text-[#16A34A]" fill="currentColor" ',
                                file_get_contents(public_path( 'assets/icons/users.svg' ))
                                ) !!}
                        </div>
                        
                        <div class="flex flex-row gap-1 items-center">
                            <img src="{{ asset('assets/icons/arrow-up.svg') }}" class="mt-[3px]" alt="">
                            <p class="text-[#16A34A]">22%</p>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <p class="text-caption">Monthly Revenue</p>
                        <h1 class='font-bold text-2xl'>RP 8.4 M</h1>
                        <p class="text-caption">Keep it up</p>
                    </div>
                </div>
            </div>

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
    
    {{-- Profile section --}}
    <div class="flex flex-col p-6 gap-6 bg-white rounded-md border-grey-border border max-w-[300px]">
        {{-- User card --}}
        <div class="flex flex-col gap-4 justify-start">
            {{-- User Information --}}
            <div class="flex flex-col gap-4 lg:flex-row transition-all duration-300"> 
                <img src="{{ $user->photo_url ? asset($user->photo_url) : ($user->gender=="female" ? asset('assets/icons/user_female.svg') : asset('assets/icons/user_male.svg')) }}" class="rounded-full w-16 h-16 lg:mx-0 mx-auto" alt="pfp"> 
                <div class="flex flex-col justify-left text-left"> 
                    <h4 class="user-name font-semibold "> {{ $user->full_name }} </h4> 
                    <p class="text-caption">{{ $user->psychologist->title }}</p> 
                    <div class="flex gap-2 items-center "> 
                        <div class="rounded-full w-2 h-2 bg-primary"></div> 
                        <p class="text-primary text-sm">Active</p> 
                    </div> 
                </div> 
            </div>

            {{-- Action --}}
            <div class="flex gap-4 flex-col lg:flex-row">
                <x-rounded-button text="Settings" active="true" route="{{ route('profile.index') }}"></x-rounded-button>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="rounded-button secondary bg-white hover:bg-gray-100 text-caption-dark border border-grey-border rounded-md  px-2 md:px-4 py-2 md:py-2 text-center flex flex-1 items-center justify-center text-xs sm:text-sm lg:text-base">
                        Logout
                    </button>
                </form>
            </div>
            
        </div>

        @include('components.notifications')
        
    </div>
@endsection