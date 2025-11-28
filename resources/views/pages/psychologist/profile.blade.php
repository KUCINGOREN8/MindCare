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
                        <img src="{{ asset($psychologist->photo_url) }}" alt="" class='rounded-full mb-4' style='width:200px;'>
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
                <div class="flex flex-col gap-3">
                    <h1 class="font-semibold text-lg">About {{ $psychologist->full_name}}</h1>
                    <h5 class="text-captiondark text-sm">{{$psychologist->about_me}}</h5>
                </div>
            </div>

            <div class="flex flex-col bg-white p-6 rounded-md border-grey-border border justify-between gap-6">
                <div class="flex flex-col gap-3">
                    <h1 class="font-semibold text-lg">Education</h1>
                    <h5 class="text-captiondark text-sm">{{$psychologist->about_me}}</h5>
                </div>
            </div>

            <div class="flex flex-col bg-white p-6 rounded-md border-grey-border border justify-between gap-6">
                <div class="flex flex-col gap-3">
                    <h1 class="font-semibold text-lg">Experiences</h1>
                    <h5 class="text-captiondark text-sm">{{$psychologist->about_me}}</h5>
                </div>
            </div>

            <div class="flex flex-col bg-white p-6 rounded-md border-grey-border border justify-between gap-6">
                <div class="flex flex-col gap-3">
                    <h1 class="font-semibold text-lg">Available Schedule</h1>
                    <h5 class="text-captiondark text-sm">{{$psychologist->about_me}}</h5>
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
                    <h5 class="text-captiondark text-sm">{{$psychologist->about_me}}</h5>
                </div>
            </div>
        </div>
    </div>
@endsection