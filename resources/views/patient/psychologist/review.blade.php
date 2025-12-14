@extends('layouts.dashboard')

@section('title')
{{ $psychologist->user->full_name }} Reviews
@endsection

@section('content')
    <div class="flex flex-1 flex-col gap-6">
        <div class="flex flex-col gap-6">
            <div class="flex bg-white p-6 rounded-md border-grey-border border justify-between">
                <div class="flex flex-col">
                    <h1 class="font-bold text-lg text-primary">{{$psychologist->user->full_name}}</h1>
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
                        <div class="flex flex-1 p-[16px] rounded-md border border-1 border-grey-border gap-3 items-center">
                            <img src="{{ $review->user->photo_url }}" 
                                alt="" 
                                class="w-[35px] h-[35px] rounded-full object-cover">
                            <div class="flex flex-col gap-2 flex-1">
                                <p class="text-sm wrap-break-word whitespace-normal">
                                    {{ $review->review }}
                                </p>
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
@endsection
