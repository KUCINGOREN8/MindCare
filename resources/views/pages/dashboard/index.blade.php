@extends('layouts.dashboard')

@php
// ini dummy data, nnti sesuain dgn data di database
$notifications = [
    [
        'icon' => 'assets/icons/calendar.svg',
        'title' => 'Session Reminder',
        'message' => 'Your session with Dr. Emily Chen starts in 2 hours',
        'time' => '1 hour ago',
        'type' => 'reminder',
    ],
    [
        'icon' => 'assets/icons/check.svg',
        'title' => 'Mood Entry Complete',
        'message' => 'Great job logging your mood for 7 days straight!',
        'time' => '3 hours ago',
        'type' => 'achievement',
    ],
    [
        'icon' => 'assets/icons/messages.svg',
        'title' => 'New Message',
        'message' => 'Dr. Rodriguez sent you a follow-up message',
        'time' => '5 hours ago',
        'type' => 'message',
    ],
    [
        'icon' => 'assets/icons/tips.svg',
        'title' => 'Daily Tip',
        'message' => 'Try a 5-minute meditation to start your day',
        'time' => '1 day ago',
        'type' => 'tip',
    ],
];
@endphp

@section('title')
Dashboard
@endsection

@section('content')
    <div class="flex flex-1">
        <div class="flex flex-col flex-1 gap-6">
            <div class="flex flex-col bg-white p-6 gap-4 rounded-md border-grey-border border">
                <div class="flex flex-col">
                    <h1 class="text-primary font-bold text-lg">Good Day, {{ $user->full_name }}!</h1>
                    <h5 class="text-captiondark text-sm">How are you feeling today?</h5>
                </div>
                @if($user->role == 'patient')
                <div class="flex items-center gap-4">
                    <p class="text-caption-dark">Rate your mood:</p>
                    <div class="flex gap-4">
                        <button class="p-2 rounded-full bg-transparent hover:bg-primary/5 "><img src="{{ asset("assets/dashboard/sad.png") }}" alt="Sad"></button>
                        <button class="p-2 rounded-full bg-transparent hover:bg-primary/5 "><img src="{{ asset("assets/dashboard/flat.png") }}" alt="Sad"></button>
                        <button class="p-2 rounded-full bg-transparent hover:bg-primary/5 "><img src="{{ asset("assets/dashboard/good.png") }}" alt="Sad"></button>
                        <button class="p-2 rounded-full bg-transparent hover:bg-primary/5 "><img src="{{ asset("assets/dashboard/happy.png") }}" alt="Sad"></button>
                        <button class="p-2 rounded-full bg-transparent hover:bg-primary/5 "><img src="{{ asset("assets/dashboard/blissful.png") }}" alt="Sad"></button>
                    </div>
                </div>
                @endif
            </div>

            @include('components.upcoming-appointment')
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
                    <p class="text-caption">Premium Member</p> 
                    <div class="flex gap-2 items-center "> 
                        <div class="rounded-full w-2 h-2 bg-primary"></div> 
                        <p class="text-primary text-sm">Active</p> 
                    </div> 
                </div> 
            </div>

            {{-- Action --}}
            <div class="flex gap-4 flex-col lg:flex-row">
                <x-rounded-button text="Settings" active="true" route="{{ route('profile.edit') }}"></x-rounded-button>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="rounded-button secondary bg-white hover:bg-caption/2 text-caption-dark border border-grey-border rounded-md  px-2 md:px-4 py-2 md:py-2 text-center flex flex-1 items-center justify-center text-xs sm:text-sm lg:text-base">
                        Logout
                    </button>
                </form>
            </div>
            
        </div>

        @include('components.notifications')
        
    </div>
@endsection