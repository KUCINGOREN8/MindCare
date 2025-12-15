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
                    <h5 class="text-captiondark text-sm">{{ __('messages.mood') }}</h5>
                </div>

                {{-- FORM MOOD --}}
                <form action="{{ route('patient.mood.store') }}" method="POST" {{--
                        PERUBAHAN DI SINI:
                        Kita menyuntikkan array translation ke dalam Alpine.js (moodLabels)
                    --}} x-data="{
                    selected: '{{ optional($todayMood)->mood }}',
                    hasSubmittedToday: {{ optional($todayMood)->mood ? 'true' : 'false' }},
                    moodLabels: {
                        'sad': '{{ __('moods.sad') }}',
                        'flat': '{{ __('moods.flat') }}',
                        'good': '{{ __('moods.good') }}',
                        'happy': '{{ __('moods.happy') }}',
                        'blissful': '{{ __('moods.blissful') }}'
                    }
                }">

                    @csrf
                    <input type="hidden" name="mood" :value="selected">

                    <div class="flex items-center gap-4">
                        <p class="text-caption-dark">
                            <span x-show="!hasSubmittedToday">{{ __('messages.ratemood') }}:</span>
                            <span x-show="hasSubmittedToday" class="flex flex-col">
                                <span>
                                    {{ __('messages.todaymood') }}:
                                    {{-- GANTI x-text AGAR MENGAMBIL DARI TRANSLATION --}}
                                    <span class="font-medium capitalize text-primary" x-text="moodLabels[selected]"></span>
                                </span>
                                <span class="text-sm text-gray-500">({{ __('messages.updatemood') }})</span>
                            </span>
                        </p>

                        <div class="flex gap-4">
                            @php
                                // Array Data Mood (ID & Gambar)
                                $moodOptions = [
                                    ['id' => 'sad', 'img' => 'sad.png'],
                                    ['id' => 'flat', 'img' => 'flat.png'],
                                    ['id' => 'good', 'img' => 'good.png'],
                                    ['id' => 'happy', 'img' => 'happy.png'],
                                    ['id' => 'blissful', 'img' => 'blissful.png'],
                                ];
                            @endphp

                            @foreach ($moodOptions as $mood)
                                <button type="button"
                                    @click="selected = '{{ $mood['id'] }}'; hasSubmittedToday = true; $nextTick(() => $el.closest('form').submit())"
                                    :class="selected === '{{ $mood['id'] }}'
                                        ?
                                        'bg-secondary text-white scale-110' :
                                        'bg-transparent hover:bg-secondary/10'"
                                    class="p-2 rounded-full transition transform duration-300 hover:scale-110"
                                    {{-- Title hover juga kita translate --}}
                                    :title="hasSubmittedToday && selected === '{{ $mood['id'] }}' ?
                                        '{{ __('messages.click_to_change') }}' : ''">

                                    <img src="{{ asset('assets/dashboard/' . $mood['img']) }}"
                                        alt="{{ __('moods.' . $mood['id']) }}" class="w-8 h-8">
                                </button>
                            @endforeach
                        </div>
                    </div>
                </form>
            </div>

            @include('components.upcoming-appointment')

            <x-mood-chart :chartData="$moodData['chartData']" :averageMood="$moodData['averageMood']" :note="$moodData['note']" height="250px" id="patientMoodChart" />
        </div>
    </div>

    <x-user-profile-card :user="$user" :notifications="$notifications" />
@endsection
