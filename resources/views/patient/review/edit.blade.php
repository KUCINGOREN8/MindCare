@extends('layouts.dashboard')

@section('title', 'Edit Your Review')

@section('content')
    <div class="flex flex-1 flex-col gap-6">
        <div class="flex flex-col gap-6">
            <div class="flex bg-white p-6 rounded-md border-grey-border border justify-between">
                <div class="flex flex-col">
                    <h1 class="font-bold text-lg text-primary">Edit Your Review</h1>
                    <h5 class="text-captiondark text-sm">Update your experience with {{ $appointment->psychologist->user->full_name }}</h5>
                </div>
                <button onclick="window.history.back()" type="submit" class="px-4 py-2 bg-primary text-white rounded-md">Back</button>
            </div>
        
            {{-- Appointment --}}
            <x-appointment-card
                :appointment="$appointment"
                :showButtons="false"
            />
        
            {{-- Review form --}}
            @include('patient.review.partials.review-form', [
                'action' => route('patient.appointments.review.update', $appointment->id),
                'method' => 'PUT',
                'appointment' => $appointment,
                'review' => $review,
                'type' => 'edit',
            ])
        
            {{-- Delete --}}
            <div class="mt-8 pt-8 border-t border-gray-200">
                <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-red-800 mb-2">Delete Review</h3>
                    <p class="text-red-700 mb-4">
                        Once you delete your review, you won't be able to recover it.
                    </p>
                    <form action="{{ route('patient.appointments.review.destroy', $appointment->id) }}" 
                            method="POST" 
                            id="delete-review-form">
                        @csrf
                        @method('DELETE')
                        <button type="button" 
                                onclick="confirmDelete()"
                                class="px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                            Delete Review
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const reviewTextarea = document.getElementById('review');
        const charCount = document.getElementById('char-count');
        const form = document.querySelector('.review-form');
        const submitBtn = document.getElementById('submit-btn');
        const submitText = document.getElementById('submit-text');
        const submitSpinner = document.getElementById('submit-spinner');
        
        updateCharCount();
        
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
            submitText.textContent = 'Updating...';
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
        
        window.confirmDelete = function() {
            if (confirm('Are you sure you want to delete this review? This action cannot be undone.')) {
                document.getElementById('delete-review-form').submit();
            }
        }
    });
    </script>
    @endpush
@endsection