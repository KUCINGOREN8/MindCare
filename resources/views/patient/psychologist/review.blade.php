@extends('layouts.dashboard')

@section('title')
    {{-- Menggunakan parameter :name untuk menyisipkan nama --}}
    {{ __('reviews.page_title', ['name' => $psychologist->user->full_name]) }}
@endsection

@section('content')
    <div class="flex flex-1 flex-col gap-4 sm:gap-6 w-full">
        <div class="flex flex-col gap-4 sm:gap-6">
            {{-- HEADER --}}
            <div
                class="flex flex-col sm:flex-row bg-white p-4 sm:p-6 rounded-md border-grey-border border justify-between items-start sm:items-center gap-4 sm:gap-0">
                <div class="flex flex-col">
                    <h1 class="font-bold text-[#00C3B3] text-lg">{{ $psychologist->user->full_name }}</h1>
                    <h5 class="text-captiondark text-sm sm:text-base">{{ __('reviews.subtitle') }}</h5>
                </div>
                <button onclick="window.history.back()" type="submit"
                    class="w-full sm:w-auto px-4 py-2 bg-[#00C3B3] hover:bg-[#179990] text-white rounded-md flex items-center justify-center text-sm font-medium transition-colors">
                    {{ __('reviews.back') }}
                </button>
            </div>

            {{-- REVIEWS LIST --}}
            <div class="flex flex-col bg-white p-4 sm:p-6 rounded-md border-grey-border border gap-6">
                <div class="flex flex-col gap-3">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                        <div class="flex flex-col gap-1">
                            <h1 class="font-semibold text-lg">{{ __('reviews.section_title') }}</h1>
                            <div class="flex items-center gap-2">
                                <img src="{{ asset('assets/icons/star.png') }}" alt="" class="w-4 h-4">
                                <p class="text-sm font-medium">
                                    {{ number_format($psychologist->reviews->avg('rating'), 1) }}/5.0</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-4">
                        @foreach ($psychologist->reviews as $review)
                            <div
                                class="flex flex-col sm:flex-row p-4 rounded-md border border-grey-border gap-4 items-start transition hover:shadow-sm">
                                <img src="{{ $review->user->photo_url }}" alt=""
                                    class="w-10 h-10 rounded-full object-cover flex-shrink-0">
                                <div class="flex flex-col gap-2 flex-1 w-full min-w-0">
                                    <p class="text-sm text-gray-700 break-words leading-relaxed">
                                        {{ $review->review }}
                                    </p>

                                    <div class="flex flex-wrap items-center justify-between gap-3 mt-1">
                                        <div class="flex gap-1">
                                            @for ($i = 0; $i < 5; $i++)
                                                @if ($i < $review->rating)
                                                    <img src="{{ asset('assets/icons/star.png') }}" alt=""
                                                        class="w-3 h-3 sm:w-4 sm:h-4">
                                                @else
                                                    {{-- Opsional: Tampilkan bintang kosong (abu-abu) jika rating < 5 --}}
                                                    <img src="{{ asset('assets/icons/star_gray.png') }}" alt=""
                                                        class="w-3 h-3 sm:w-4 sm:h-4 grayscale opacity-30">
                                                @endif
                                            @endfor
                                        </div>

                                        <p class="text-xs text-gray-400">
                                            {{ $review->created_at->translatedFormat('d M Y H:i') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
