@props([
    'action' => '',
    'method' => 'POST',
    'appointment' => null,
    'review' => null,
    'type' => 'create', // create or edit
])

@php
    $isEdit = $type === 'edit';
    $existingReview = $review ?? null;
    $currentRating = $existingReview ? $existingReview->rating : 0;
    $currentReviewText = $existingReview ? $existingReview->review : old('review', '');
@endphp

<div class="flex flex-col bg-white p-6 gap-4 rounded-md border-grey-border border">

    <form action="{{ $action }}" method="POST" class="review-form">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        {{-- Star Rating --}}
        <div class="mb-8">
            <label class="block text-sm mb-4">
                {{ __('review_form.rating_label') }}
                <span class="text-red-500">*</span>
            </label>

            <x-star-rating name="rating" :value="$currentRating" size="lg" :editable="true" class="mb-4" />
        </div>

        {{-- Review Text --}}
        <div class="mb-8">
            <label for="review" class="block text-sm mb-2">
                {{ __('review_form.review_label') }}
            </label>
            <textarea id="review" name="review" rows="5"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"
                placeholder="{{ __('review_form.placeholder') }}" maxlength="1000">{{ $currentReviewText }}</textarea>
            <div class="flex justify-between mt-1">
                <p class="text-xs text-gray-500">{{ __('review_form.visibility_note') }}</p>
                <p class="text-xs text-gray-500" id="char-count">0/1000</p>
            </div>
            @error('review')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Form Actions --}}
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('patient.appointments.index') }}"
                class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors text-center">
                {{ $isEdit ? __('review_form.cancel') : __('review_form.skip') }}
            </a>
            <button type="submit" id="submit-btn"
                class="flex-1 px-6 py-3 bg-primary text-white font-medium rounded-lg hover:bg-[#179990] focus:outline-none focus:ring-2 focus:ring-[#179990] focus:ring-offset-2 transition-colors flex items-center justify-center">
                <span id="submit-text">
                    {{ $isEdit ? __('review_form.update') : __('review_form.submit') }}
                </span>
                <svg id="submit-spinner" class="hidden w-5 h-5 ml-2 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
            </button>
        </div>
    </form>
</div>
