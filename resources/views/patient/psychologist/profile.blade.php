@extends('layouts.dashboard')

@section('title')
    {{ $psychologist->user->full_name }} Profile
@endsection

@section('content')
    <div class="flex flex-1">
        <div class="flex flex-col flex-1 gap-6">
            {{-- HEADER INFO --}}
            <div class="flex flex-col bg-white p-6 rounded-md border-grey-border border justify-between gap-6">

                <div class="flex flex-col md:flex-row justify-between gap-3">
                    <div class="flex flex-1 flex-col items-center justify-center text-center ">
                        <img src="{{ $psychologist->user->photo_url }}"
                            class="rounded-full w-24 h-24 lg:mx-0 mx-auto object-cover" alt="">
                        <h1 class="font-semibold text-lg"> {{ $psychologist->user->full_name }} </h1>
                        <h5 class="text-captiondark text-sm"> {{ $psychologist->title }} </h5>
                    </div>

                    <div class="flex flex-1 flex-col">
                        <div>
                            <h5 class="text-captiondark text-sm font-semibold">{{ __('psychologist_profile.short_bio') }}
                            </h5>
                            <p class="text-captiondark text-sm">{{ $psychologist->short_bio }}</p>
                        </div>
                        <div>
                            <h5 class="text-captiondark text-sm font-semibold">{{ __('psychologist_profile.license_no') }}
                            </h5>
                            <p class="text-captiondark text-sm">{{ $psychologist->license_number }}</p>
                        </div>
                        <div>
                            <h5 class="text-captiondark text-sm font-semibold">{{ __('psychologist_profile.languages') }}
                            </h5>
                            <p class="text-captiondark text-sm">{{ implode(', ', (array) $psychologist->languages) }}</p>
                        </div>
                        <div>
                            <h5 class="text-captiondark text-sm font-semibold">{{ __('psychologist_profile.experience') }}
                            </h5>
                            <p class="text-captiondark text-sm">
                                {{ $psychologist->years_experience ? $psychologist->years_experience . ' ' . __('psychologist_profile.years') : 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <h5 class="text-captiondark text-sm font-semibold">
                                {{ __('psychologist_profile.specialization') }}</h5>
                            <p class="text-captiondark text-sm">{{ $psychologist->specialization }}</p>
                        </div>
                        <div>
                            <h5 class="text-captiondark text-sm font-semibold">
                                {{ __('psychologist_profile.consultation_fee') }}</h5>
                            <p class="text-captiondark text-sm">Rp.
                                {{ number_format($psychologist->consultation_fee, 0, ',', '.') }}
                                {{ __('psychologist_profile.per_minutes') }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4 flex-col lg:flex-row">
                    <a href="{{ route('patient.find.psychologist') }}"
                        class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition text-center">{{ __('psychologist_profile.back') }}</a>
                    <x-rounded-button text="{{ __('psychologist_profile.book') }}" active="true"
                        route="{{ route('patient.book.appointment', $psychologist->id) }}"></x-rounded-button>
                </div>
            </div>

            {{-- ABOUT ME --}}
            <div class="flex flex-col bg-white p-6 rounded-md border-grey-border border justify-between gap-6">
                <div class="flex flex-col">
                    <h1 class="font-semibold text-lg mb-[10px]">
                        {{ __('psychologist_profile.about', ['name' => $psychologist->user->full_name]) }}</h1>
                    <p class="text-captiondark text-sm">{{ $psychologist->about_me }}</p>
                </div>
            </div>

            {{-- EDUCATION --}}
            <div class="flex flex-col bg-white p-6 rounded-md border-grey-border border justify-between gap-6">
                <div class="flex flex-col">
                    <h1 class="font-semibold text-lg mb-[10px] ">{{ __('psychologist_profile.education') }}</h1>
                    @foreach ($psychologist->educations as $edu)
                        <p class="text-captiondark text-sm">
                            {{ $edu->degree . ' - ' . $edu->institution . ' (' . $edu->year . ')' }}</p>
                    @endforeach
                </div>
            </div>

            {{-- EXPERIENCES --}}
            <div class="flex flex-col bg-white p-6 rounded-md border-grey-border border justify-between gap-6">
                <div class="flex flex-col">
                    <h1 class="font-semibold text-lg mb-[10px]">{{ __('psychologist_profile.experiences_title') }}</h1>
                    @foreach ($psychologist->experiences as $exp)
                        <p class="text-captiondark text-sm">
                            {{ $exp->position .
                                ' - ' .
                                $exp->organization .
                                ($exp->start_year
                                    ? ' (' . $exp->start_year . '-' . ($exp->end_year ? $exp->end_year : __('psychologist_profile.present')) . ')'
                                    : null) }}
                        </p>
                    @endforeach
                </div>
            </div>

            {{-- SCHEDULE --}}
            <div class="flex flex-col bg-white p-6 rounded-md border-grey-border border justify-between gap-6">
                <div class="flex flex-col">
                    <h1 class="font-semibold text-lg mb-[10px]">{{ __('psychologist_profile.available_schedule') }}</h1>

                    @php
                        // List hari dalam bahasa Inggris untuk Key Database
                        $allDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                        $schedules = $psychologist->schedules;
                    @endphp

                    @foreach ($allDays as $day)
                        @php
                            $schedule = $schedules->firstWhere('day_of_week', strtolower($day));
                            // Translate Nama Hari (Menggunakan helper Carbon atau file lang)
                            $translatedDay = \Carbon\Carbon::parse($day)->translatedFormat('l');
                        @endphp
                        <p
                            class="text-captiondark text-sm flex justify-between md:justify-start gap-8 border-b border-gray-100 py-2 last:border-0">
                            <span class="w-24 font-medium">{{ $translatedDay }}:</span>
                            <span>
                                @if ($schedule && $schedule->start_time && $schedule->end_time)
                                    {{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}
                                @else
                                    -
                                @endif
                            </span>
                        </p>
                    @endforeach
                </div>
            </div>

            {{-- REVIEWS --}}
            <div class="flex flex-col bg-white p-6 rounded-md border-grey-border border justify-between gap-6">
                <div class="flex flex-col gap-3">
                    <div class="flex justify-between items-center">
                        <div class="flex flex-col">
                            <h1 class="font-semibold text-lg">{{ __('psychologist_profile.reviews') }}</h1>
                            <div class="flex gap-2 items-center">
                                <img src="{{ asset('assets/icons/star.png') }}" alt="" class="w-4 h-4">
                                <p class="text-sm font-medium">
                                    {{ number_format($psychologist->reviews->avg('rating'), 1) }}/5.0</p>
                            </div>
                        </div>
                        <a class="text-primary hover:underline text-sm font-medium"
                            href="{{ route('patient.psychologist.review', $psychologist->id) }}">{{ __('psychologist_profile.see_all') }}</a>
                    </div>
                    @foreach ($psychologist->reviews->take(2) as $review)
                        <div class="flex flex-1 p-4 rounded-md border border-grey-border gap-3 items-start">
                            <img src="{{ $review->user->photo_url }}" class="w-[35px] h-[35px] rounded-full object-cover">
                            <div class="flex flex-col gap-1 flex-1">
                                <div class="flex justify-between items-start">
                                    <div class="flex items-center gap-1">
                                        @for ($i = 0; $i < 5; $i++)
                                            @if ($i < $review->rating)
                                                <img src="{{ asset('assets/icons/star.png') }}" alt=""
                                                    class="w-3 h-3">
                                            @else
                                                <img src="{{ asset('assets/icons/star_gray.png') }}" alt=""
                                                    class="w-3 h-3 grayscale opacity-30">
                                            @endif
                                        @endfor
                                    </div>
                                    <p class="text-xs text-gray-400">
                                        {{ $review->created_at->translatedFormat('d M Y H:i') }}</p>
                                </div>
                                <p class="text-sm text-gray-700 mt-1">{{ $review->review }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
