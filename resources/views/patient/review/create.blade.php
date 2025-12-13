@extends('layouts.dashboard')

@section('title', 'Rate Your Session')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="text-center mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">How was your session?</h1>
            <p class="text-gray-600">Share your experience with {{ $appointment->psychologist->user->full_name }}</p>
        </div>

        {{-- Check if already reviewed --}}
        @if($existingReview)
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 mb-8">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-yellow-800 font-medium">You've already reviewed this session</p>
                </div>
                <p class="text-yellow-700 mt-2">
                    You can <a href="{{ route('patient.appointments.review.edit', $appointment->id) }}" class="underline font-medium hover:text-yellow-900">edit your review</a> 
                    or <a href="{{ route('patient.appointments.index') }}" class="underline font-medium hover:text-yellow-900">go back to appointments</a>.
                </p>
            </div>
        @else
            {{-- Review Form --}}
            @include('patient.review.partials.review-form', [
                'action' => route('patient.appointments.review.store', $appointment->id),
                'method' => 'POST',
                'appointment' => $appointment,
                'type' => 'create',
            ])
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Character count for review textarea
    const reviewTextarea = document.getElementById('review');
    const charCount = document.getElementById('char-count');
    const form = document.querySelector('.review-form');
    const submitBtn = document.getElementById('submit-btn');
    const submitText = document.getElementById('submit-text');
    const submitSpinner = document.getElementById('submit-spinner');
    
    // Initialize character count
    updateCharCount();
    
    // Character count update
    reviewTextarea.addEventListener('input', updateCharCount);
    
    // Form submission
    form.addEventListener('submit', function(e) {
        const rating = document.querySelector('input[name="rating"]').value;
        
        if (!rating || parseInt(rating) === 0) {
            e.preventDefault();
            showSnackbar('Please select a rating before submitting.', 'error');
            return;
        }
        
        if (reviewTextarea.value.length > 1000) {
            e.preventDefault();
            showSnackbar('Review cannot exceed 1000 characters.', 'error');
            return;
        }
        
        // Show loading state
        submitText.textContent = 'Submitting...';
        submitSpinner.classList.remove('hidden');
        submitBtn.disabled = true;
    });
    
    function updateCharCount() {
        const length = reviewTextarea.value.length;
        charCount.textContent = `${length}/1000`;
        
        if (length > 1000) {
            charCount.classList.add('text-red-600');
        } else {
            charCount.classList.remove('text-red-600');
        }
    }
    
    function showSnackbar(message, type = 'error') {
        window.dispatchEvent(new CustomEvent('open-snackbar', {
            detail: { message, type }
        }));
    }
});
</script>
@endpush
@endsection