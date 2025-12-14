@extends('layouts.dashboard')

@section('title')
{{ $psychologist->user->full_name }} Profile
@endsection

@section('content')
    <div class="flex flex-1">
        <div class="flex flex-col flex-1 gap-6">
            <div class="flex flex-col  bg-white p-6 rounded-md border-grey-border border justify-between gap-6">

                <div class="flex flex-col md:flex-row justify-between gap-3">
                    <div class="flex flex-1 flex-col items-center justify-center text-center ">
                        <img src="{{ $psychologist->user->photo_url }}" class="rounded-full w-24 h-24 lg:mx-0 mx-auto" alt="">
                        <h1 class="font-semibold text-lg"> {{ $psychologist->user->full_name }} </h1>
                        <h5 class="text-captiondark text-sm"> {{ $psychologist->title }} </h5>
                    </div>

                    <div class="flex flex-1 flex-col">
                        <div>
                            <h5 class="text-captiondark text-sm font-semibold">Short Bio:</h5>
                            <p class="text-captiondark text-sm">{{ $psychologist->short_bio }}</p>
                        </div>
                        <div>
                            <h5 class="text-captiondark text-sm font-semibold">No. License:</h5>
                            <p class="text-captiondark text-sm">{{ $psychologist->license_number}}</p>
                        </div>
                        <div>
                            <h5 class="text-captiondark text-sm font-semibold">Languages:</h5>
                            <p class="text-captiondark text-sm">{{ implode(', ', (array) $psychologist->languages) }}</p>
                        </div>
                        <div>
                            <h5 class="text-captiondark text-sm font-semibold">Experience:</h5>
                            <p class="text-captiondark text-sm">{{ $psychologist->years_experience ? $psychologist->years_experience . ' years' : 'N/A' }}</p>
                        </div>
                        <div>
                            <h5 class="text-captiondark text-sm font-semibold">Specialization:</h5>
                            <p class="text-captiondark text-sm">{{ $psychologist->specialization }}</p>
                        </div>
                        <div>
                            <h5 class="text-captiondark text-sm font-semibold">Consultation Fee:</h5>
                            <p class="text-captiondark text-sm">Rp. {{ $psychologist->consultation_fee }} / 90 minutes</p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4 flex-col lg:flex-row">
                    <a href="{{ route('patient.find.psychologist') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Back</a>
                    <x-rounded-button text="Book" active="true" route="{{ route('patient.book.appointment', $psychologist->id) }}"></x-rounded-button>
                </div>
            </div>

            <div class="flex flex-col bg-white p-6 rounded-md border-grey-border border justify-between gap-6">
                <div class="flex flex-col">
                    <h1 class="font-semibold text-lg mb-[10px]">About {{ $psychologist->user->full_name}}</h1>
                    <p class="text-captiondark text-sm">{{$psychologist->about_me}}</p>
                </div>
            </div>

            <div class="flex flex-col bg-white p-6 rounded-md border-grey-border border justify-between gap-6">
                <div class="flex flex-col">
                    <h1 class="font-semibold text-lg mb-[10px] ">Education</h1>
                    @foreach ($psychologist->educations as $edu)
                        <p class="text-captiondark text-sm">{{ $edu->degree . " - " . $edu->institution . " (" . $edu->year . ")" }}</p>
                    @endforeach
                </div>
            </div>

            <div class="flex flex-col bg-white p-6 rounded-md border-grey-border border justify-between gap-6">
                <div class="flex flex-col">
                    <h1 class="font-semibold text-lg mb-[10px]">Experiences</h1>
                    @foreach ($psychologist->experiences as $exp)
                        <p class="text-captiondark text-sm">
                            {{ $exp->position . " - " . $exp->organization .
                                ($exp->start_year ? " (" . $exp->start_year . "-" .
                                ($exp->end_year ? $exp->end_year : "present") . ")" : null)
                            }}
                        </p>
                    @endforeach
                </div>
            </div>

            <div class="flex flex-col bg-white p-6 rounded-md border-grey-border border justify-between gap-6">
                <div class="flex flex-col">
                    <h1 class="font-semibold text-lg mb-[10px]">Available Schedule</h1>

                    @php
                        $allDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                        $schedules = $psychologist->schedules;
                    @endphp

                    @foreach ($allDays as $day)
                        @php
                            $schedule = $schedules->firstWhere('day_of_week', strtolower($day));
                        @endphp
                        <p class="text-captiondark text-sm">
                            {{ $day }}:
                            @if ($schedule && $schedule->start_time && $schedule->end_time)
                                {{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}
                            @else
                                -
                            @endif
                        </p>
                    @endforeach
                </div>
            </div>

            <div class="flex flex-col bg-white p-6 rounded-md border-grey-border border justify-between gap-6">
                <div class="flex flex-col gap-3">
                    <div class="flex justify-between">
                        <div class="flex flex-col">
                            <h1 class="font-semibold text-lg">Reviews</h1>
                            <div class="flex gap-2">
                                <img src="{{ asset('assets/icons/star.png') }}" alt="">
                                <p class="text-sm">{{ number_format($psychologist->reviews->avg('rating'),1)}}/5.0</p>
                            </div>
                        </div>
                        <a class="text-caption underline" href="{{ route('patient.psychologist.review', $psychologist->id) }}">See All</a>
                    </div>
                    @foreach ($psychologist->reviews->take(2) as $review)
                        <div class="flex flex-1 p-[16px] rounded-md border border-1 border-grey-border gap-3 items-center">
                            <img src="{{ $review->user->photo_url }}" class="w-[35px] h-[35px] rounded-full object-cover">
                            <div class="flex flex-col gap-2 flex-1">
                                <p class="text-sm wrap-break-word whitespace-normal">{{ $review->review }}</p>
                                <div class="inline-flex  tracking-wide w-fit">
                                    @for ($i = 0; $i < $review->rating; $i++)
                                        <img src="{{ asset('assets/icons/star.png') }}" alt="" class="w-3 h-3">
                                    @endfor
                                </div>
                            </div>
                            <div>
                                <p class="text-caption">{{ $review->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
