@extends('layouts.dashboard')

@section('title')
    {{-- Menggunakan parameter :name untuk menyisipkan nama --}}
    {{ __('reviews.page_title', ['name' => $psychologist->user->full_name]) }}
@endsection

@section('content')
    <div class="flex flex-1 flex-col gap-6">
        <div class="flex flex-col gap-6">
            {{-- HEADER --}}
            <div class="flex bg-white p-6 rounded-md border-grey-border border justify-between">
                <div class="flex flex-col">
                    <h1 class="font-bold text-lg text-primary">{{ $psychologist->user->full_name }}</h1>
                    <h5 class="text-captiondark text-sm">{{ __('reviews.subtitle') }}</h5>
                </div>
                <button onclick="window.history.back()" type="submit" class="px-4 py-2 bg-primary text-white rounded-md">
                    {{ __('reviews.back') }}
                </button>
            </div>

            {{-- REVIEWS LIST --}}
            <div class="flex flex-col bg-white p-6 rounded-md border-grey-border border justify-between gap-6">
                <div class="flex flex-col gap-3">
                    <div class="flex justify-between">
                        <div class="flex flex-col">
                            <h1 class="font-semibold text-lg">{{ __('reviews.section_title') }}</h1>
                            <div class="flex gap-2">
                                <img src="{{ asset('assets/icons/star.png') }}" alt="">
                                <p class="text-sm">{{ number_format($psychologist->reviews->avg('rating'), 1) }}/5.0</p>
                            </div>
                        </div>
                    </div>

                    @foreach ($psychologist->reviews as $review)
                        <div class="flex flex-1 p-[16px] rounded-md border border-1 border-grey-border gap-3 items-center">
                            <img src="{{ $review->user->photo_url }}" alt=""
                                class="w-[35px] h-[35px] rounded-full object-cover">
                            <div class="flex flex-col gap-2 flex-1">
                                <p class="text-sm wrap-break-word whitespace-normal">
                                    {{ $review->review }}
                                </p>
                                <div class="inline-flex tracking-wide w-fit">
                                    @for ($i = 0; $i < 5; $i++)
                                        @if ($i < $review->rating)
                                            <img src="{{ asset('assets/icons/star.png') }}" alt="" class="w-3 h-3">
                                        @else
                                            {{-- Opsional: Tampilkan bintang kosong (abu-abu) jika rating < 5 --}}
                                            <img src="{{ asset('assets/icons/star_gray.png') }}" alt=""
                                                class="w-3 h-3 grayscale opacity-30">
                                        @endif
                                    @endfor
                                </div>
                            </div>
                            <div>
                                {{-- PENTING: Gunakan translatedFormat agar nama bulan berubah (Des, Jan, Feb) --}}
                                <p class="text-caption">{{ $review->created_at->translatedFormat('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
