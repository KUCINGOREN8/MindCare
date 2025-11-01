@extends('layouts.user.dashboard')

@php
$navItems = [
    ['icon' => 'image/icons/home.svg', 'text' => 'Dashboard', 'route' => '/user/customer/dashboard', 'active' => true],
    ['icon' => 'image/icons/find-user.svg', 'text' => 'Find Psychologist', 'route' => '/user/customer/find-psychologist'],
    ['icon' => 'image/icons/book.svg', 'text' => 'Book Appointment', 'route' => '/user/customer/book_appointment'],
    ['icon' => 'image/icons/calendar.svg', 'text' => 'Appointments', 'route' => '/user/customer/appointments'],
    ['icon' => 'image/icons/messages.svg', 'text' => 'Messages', 'route' => '/user/customer/messages'],
];

// ini sesuain data di database
$notifications = [
    [
        'icon' => 'image/icons/calendar.svg',
        'title' => 'Session Reminder',
        'message' => 'Your session with Dr. Emily Chen starts in 2 hours',
        'time' => '1 hour ago',
        'type' => 'reminder',
    ],
    [
        'icon' => 'image/icons/calendar.svg',
        'title' => 'Mood Entry Complete',
        'message' => 'Great job logging your mood for 7 days straight!',
        'time' => '3 hours ago',
        'type' => 'achievement',
    ],
    [
        'icon' => 'image/icons/messages.svg',
        'title' => 'New Message',
        'message' => 'Dr. Rodriguez sent you a follow-up message',
        'time' => '5 hours ago',
        'type' => 'message',
    ],
    [
        'icon' => 'image/icons/calendar.svg',
        'title' => 'Daily Tip',
        'message' => 'Try a 5-minute meditation to start your day',
        'time' => '1 day ago',
        'type' => 'tip',
    ],
];
@endphp

@section('content')
    <div class="flex flex-1">
        <div class="flex flex-col flex-1 gap-6">
            <div class="flex flex-col bg-white p-6 gap-4 rounded-md border-grey-border border">
                <div class="flex flex-col">
                    <h1 class="text-primary font-bold text-lg">Good Day, Jane Cooper!</h1>
                    <h5 class="text-captiondark text-sm">How are you feeling today?</h5>
                </div>
                <div class="flex items-center gap-4">
                    <p class="text-caption-dark">Rate your mood:</p>
                    <div class="flex gap-4">
                        <button class="p-2 rounded-full bg-transparent hover:bg-primary/5 "><img src="{{ asset("image/emoji/sad.png") }}" alt="Sad"></button>
                        <button class="p-2 rounded-full bg-transparent hover:bg-primary/5 "><img src="{{ asset("image/emoji/flat.png") }}" alt="Sad"></button>
                        <button class="p-2 rounded-full bg-transparent hover:bg-primary/5 "><img src="{{ asset("image/emoji/good.png") }}" alt="Sad"></button>
                        <button class="p-2 rounded-full bg-transparent hover:bg-primary/5 "><img src="{{ asset("image/emoji/happy.png") }}" alt="Sad"></button>
                        <button class="p-2 rounded-full bg-transparent hover:bg-primary/5 "><img src="{{ asset("image/emoji/blissful.png") }}" alt="Sad"></button>
                    </div>
                </div>
            </div>

            @include('sections.user.dashboard.upcoming-appointment')
        </div>
    </div>
    
    {{-- Profile section --}}
    <div class="flex flex-col p-6 gap-6 bg-white rounded-md border-grey-border border max-w-[200px]">
        {{-- User card --}}
        <div class="flex flex-col gap-4 justify-start">
            {{-- User Information --}}
            <div class="flex flex-col gap-4 lg:flex-row transition-all duration-300"> 
                <img src="{{ asset("image/profile/img.png") }}" class="rounded-full w-16 h-16 lg:mx-0 mx-auto" alt="pfp"> 
                <div class="flex flex-col justify-left text-left"> 
                    <h4 class="user-name font-semibold ">Jane Cooper</h4> 
                    <p class="text-caption">Premium Member</p> 
                    <div class="flex gap-2 items-center "> 
                        <div class="rounded-full w-2 h-2 bg-primary"></div> 
                        <p class="text-primary text-sm">Active</p> 
                    </div> 
                </div> 
            </div>

            {{-- Action --}}
            <div class="flex gap-4 flex-col lg:flex-row">
                <x-rounded-button text="Settings" active="true" route="#"></x-rounded-button>
                <x-rounded-button text="Logout" secondary="true" route="#"></x-rounded-button>
            </div>
            
        </div>

        @include('sections.user.dashboard.notifications')
        
    </div>
@endsection