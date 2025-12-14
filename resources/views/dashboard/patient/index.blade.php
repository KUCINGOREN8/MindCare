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
                
                <form action="{{ route('patient.mood.store') }}" method="POST" x-data="{ selected: '{{ optional($todayMood)->mood }}', hasSubmittedToday: {{ optional($todayMood)->mood ? 'true' : 'false' }} }">
                    @csrf
                    <input type="hidden" name="mood" :value="selected">

                    <div class="flex items-center gap-4">
                        <p class="text-caption-dark">
                            <span x-show="!hasSubmittedToday">Rate your mood:</span>
                            <span x-show="hasSubmittedToday" class="flex flex-col">
                                <span>
                                    Today's mood: 
                                    <span class="font-medium capitalize" x-text="selected"></span>
                                </span>
                                <span class="text-sm text-gray-500">(click to update)</span>
                            </span>
                        </p>
                        <div class="flex gap-4">
                            @foreach ([
                                ['id' => 'sad', 'img' => 'sad.png', 'alt' => 'Sad'],
                                ['id' => 'flat', 'img' => 'flat.png', 'alt' => 'Flat'],
                                ['id' => 'good', 'img' => 'good.png', 'alt' => 'Good'],
                                ['id' => 'happy', 'img' => 'happy.png', 'alt' => 'Happy'],
                                ['id' => 'blissful', 'img' => 'blissful.png', 'alt' => 'Blissful'],
                            ] as $mood)
                                <button
                                    type="button"
                                    @click="selected = '{{ $mood['id'] }}'; hasSubmittedToday = true; $nextTick(() => $el.closest('form').submit())"
                                    :class="selected === '{{ $mood['id'] }}'
                                        ? 'bg-secondary text-white scale-110'
                                        : 'bg-transparent hover:bg-secondary/10'"
                                    class="p-2 rounded-full transition transform duration-300 hover:scale-110"
                                    :title="hasSubmittedToday && selected === '{{ $mood['id'] }}' ? 'Click to change mood' : ''"
                                >
                                    <img src="{{ asset('assets/dashboard/' . $mood['img']) }}"
                                        alt="{{ $mood['alt'] }}"
                                        class="w-8 h-8">
                                </button>
                            @endforeach
                        </div>
                    </div>
                </form>
            </div>

            @include('components.upcoming-appointment')

            <x-mood-chart 
                :chartData="$moodData['chartData']"
                :averageMood="$moodData['averageMood']"
                :note="$moodData['note']"
                height="250px"
                id="patientMoodChart"
            />
        </div>
    </div>

    <x-user-profile-card :user="$user" :notifications="$notifications" />
@endsection
