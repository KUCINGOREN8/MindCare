@extends('layouts.app')

@section('title')
{{ $psychologist->full_name }} Profile
@endsection

@section('content')
    <div class="flex flex-1">
        <div class="flex flex-col flex-1 gap-6">
            <div class="flex flex-col bg-white p-6 rounded-md border-grey-border border justify-between gap-6">

                <div class="flex justify-between gap-3">
                    <div class="flex flex-1 flex-col items-center justify-center text-center ">
                        <img src="{{ $psychologist->photo_url ? asset($psychologist->photo_url) : ($psychologist->gender=="female" ? asset('assets/icons/user_female.svg') : asset('assets/icons/user_male.svg')) }}" alt="" class='rounded-full mb-4' style='width:200px;'>
                        <h1 class="font-semibold text-lg"> {{ $psychologist->full_name }} </h1>
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
                            <p class="text-captiondark text-sm">{{ $psychologist->years_experience ? $psychologist->years_experience . 'years' : 'N/A' }}</p>
                        </div>
                        <div>
                            <h5 class="text-captiondark text-sm font-semibold">Specialization:</h5>
                            <p class="text-captiondark text-sm">{{ $psychologist->specialization }}</p>
                        </div>
                        <div>
                            <h5 class="text-captiondark text-sm font-semibold">Consultation Fee:</h5>
                            <p class="text-captiondark text-sm">Rp. {{ $psychologist->consultation_fee }} / 60 minutes</p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4 flex-col lg:flex-row">
                    <x-rounded-button text="Book" active="true" route="#"></x-rounded-button>
                </div>
            </div>

            <div class="flex flex-col bg-white p-6 rounded-md border-grey-border border justify-between gap-6">
                <div class="flex flex-col">
                    <h1 class="font-semibold text-lg mb-[10px]">About {{ $psychologist->full_name}}</h1>
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
                        $allDays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
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
                        <h1 class="font-semibold text-lg">Reviews</h1>
                        <button class="text-caption underline" href="">See All</button>
                    </div>
                    <div class="flex">
                        <p class="text-caption"></p>
                    </div>
                    @foreach ($psychologist->reviews as $review)
                        <div class="flex flex-1 p-[10px] rounded-md bg-primary/10 gap-3 items-center">
                            <img src="{{ $review->user->photo_url ? asset($review->user->photo_url) : ($review->user->gender=="female" ? asset('assets/icons/user_female.svg') : asset('assets/icons/user_male.svg')) }}" alt="" class="w-[35px] h-[35px] rounded-full">
                            <p class="text-sm wrap-break-word whitespace-normal">
                                {{ $review->review }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection