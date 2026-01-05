@extends('layouts.auth')
@section('title', 'Sign Up as Psychologist')

@section('content')
    <div class="min-h-screen bg-gray-50 w-full overflow-x-hidden">
        @include('auth.partials.progress')

        <div class="max-w-4xl mx-auto px-4 py-8">
            <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
                <h2 class="text-2xl md:text-3xl font-bold text-center mb-2" style="color: #009C8F;">
                    {{ __('messages.schedule') }}</h2>
                <p class="text-gray-600 text-center mb-8 text-sm md:text-base">Step 5:
                    {{ __('messages.scheduledesc') }}</p>

                <form method="POST" action="{{ route('psychologist.signup.storeStep5', $user) }}" id="scheduleForm">
                    @csrf
                    <div id="scheduleContainer">
                        @php
                            $isId = app()->getLocale() == 'id';
                            // $key = Value Database (monday)
                            // $label = Tampilan (Senin)
                            $days = [
                                'monday' => $isId ? 'Senin' : 'Monday',
                                'tuesday' => $isId ? 'Selasa' : 'Tuesday',
                                'wednesday' => $isId ? 'Rabu' : 'Wednesday',
                                'thursday' => $isId ? 'Kamis' : 'Thursday',
                                'friday' => $isId ? 'Jumat' : 'Friday',
                                'saturday' => $isId ? 'Sabtu' : 'Saturday',
                                'sunday' => $isId ? 'Minggu' : 'Sunday',
                            ];
                        @endphp

                        @foreach ($days as $key => $label)
                            @php
                                // PENTING: Gunakan $loop->index agar index tetap angka (0, 1, 2...)
                                // Ini agar JS dan Controller tidak bingung
                                $i = $loop->index;

                                $startTime = old('schedules.' . $i . '.start_time', '09:00');
                                $endTime = old('schedules.' . $i . '.end_time', '17:00');
                                $notAvailable = old('schedules.' . $i . '.not_available', '0') == '1';
                            @endphp

                            <div class="schedule-day bg-gray-50 p-4 md:p-6 rounded-lg mb-4 border border-gray-200">
                                <div class="flex flex-wrap items-center justify-between mb-4 gap-2">
                                    {{-- TAMPILAN: Pakai Label (Senin/Monday) --}}
                                    <h3 class="text-lg font-medium text-gray-800 capitalize">{{ $label }}</h3>

                                    <label class="flex items-center">
                                        {{-- NAME: Pakai Index Angka ($i) --}}
                                        <input type="hidden" name="schedules[{{ $i }}][not_available]"
                                            value="0">
                                        <input type="checkbox" name="schedules[{{ $i }}][not_available]"
                                            class="not-available-toggle mr-2" style="accent-color: #dc2626;"
                                            data-index="{{ $i }}" value="1"
                                            {{ $notAvailable ? 'checked' : '' }}>

                                        <span class="text-sm text-gray-600">{{ __('schedule.not_available') }}</span>
                                    </label>
                                </div>

                                {{-- ID: Pakai Index Angka ($i) agar JS step5.js jalan --}}
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 schedule-fields"
                                    id="schedule-fields-{{ $i }}"
                                    style="{{ $notAvailable ? 'display: none;' : '' }}">

                                    {{-- VALUE: Pakai Key Asli (monday) agar Database benar --}}
                                    <input type="hidden" name="schedules[{{ $i }}][day_of_week]"
                                        value="{{ $key }}">

                                    <div class="w-full min-w-0">
                                        <label
                                            class="block text-gray-700 mb-2 text-sm md:text-base">{{ __('schedule.start_time') }}
                                            <span class="text-red-500">*</span></label>
                                        <input type="time" name="schedules[{{ $i }}][start_time]"
                                            class="w-full outline-none text-black bg-white px-4 py-3 rounded-lg border border-gray-300 schedule-time text-sm md:text-base"
                                            value="{{ $startTime }}" {{ $notAvailable ? 'disabled' : 'required' }}>
                                    </div>
                                    <div class="w-full min-w-0">
                                        <label
                                            class="block text-gray-700 mb-2 text-sm md:text-base">{{ __('schedule.end_time') }}
                                            <span class="text-red-500">*</span></label>
                                        <input type="time" name="schedules[{{ $i }}][end_time]"
                                            class="w-full outline-none text-black bg-white px-4 py-3 rounded-lg border border-gray-300 schedule-time text-sm md:text-base"
                                            value="{{ $endTime }}" {{ $notAvailable ? 'disabled' : 'required' }}>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-500 mt-1 mr-3 flex-shrink-0"></i>
                            <div>
                                <h4 class="font-medium text-blue-800 mb-1 text-sm md:text-base">
                                    {{ __('schedule.important_notes') }}</h4>
                                <ul class="text-xs md:text-sm text-blue-700 space-y-1">
                                    <li>• {{ __('schedule.note_1') }}</li>
                                    <li>• {{ __('schedule.note_2') }}</li>
                                    <li>• {{ __('schedule.note_3') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-between">
                        <a href="{{ route('psychologist.signup.step4', $user) }}"
                            class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition text-center text-sm md:text-base">
                            ← {{ __('messages.back') }}
                        </a>
                        <button type="submit"
                            class="px-8 py-3 bg-[#009C8F] text-white rounded-lg font-medium hover:opacity-90 transition shadow-md text-sm md:text-base">
                            {{ __('messages.completeregis') }} →
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
