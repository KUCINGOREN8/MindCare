@extends('layouts.dashboard')

@section('title')
{{ $psychologist->user->full_name }} Reviews
@endsection

@section('content')
    <div class="flex flex-1 flex-col gap-6">
        <div class="flex flex-col gap-6">
            <div class="flex bg-white p-6 rounded-md border-grey-border border justify-between">
                <div class="flex flex-col">
                    <h1 class="font-bold text-lg">{{$psychologist->user->full_name}}</h1>
                    <h5 class="text-captiondark text-sm">Review Page</h5>
                </div>
                <button onclick="window.history.back()" type="submit" class="px-4 py-2 bg-primary text-white rounded-md">Back</button>            </div>
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
