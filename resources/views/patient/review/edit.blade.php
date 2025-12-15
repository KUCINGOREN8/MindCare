@extends('layouts.dashboard')

@section('title', __('edit_review.title'))

@section('content')
    <div class="flex flex-1 flex-col gap-6">
        <div class="flex flex-col gap-6">
            <div class="flex bg-white p-6 rounded-md border-grey-border border justify-between">
                <div class="flex flex-col">
                    <h1 class="font-bold text-lg text-primary">{{ __('edit_review.header_title') }}</h1>
                    <h5 class="text-captiondark text-sm">
                        {{ __('edit_review.header_subtitle', ['name' => $appointment->psychologist->user->full_name]) }}
                    </h5>
                </div>
                <button onclick="window.history.back()" type="submit" class="px-4 py-2 bg-primary text-white rounded-md">
                    {{ __('edit_review.back') }}
                </button>
            </div>

            {{-- Appointment --}}
            <x-appointment-card :appointment="$appointment" :showButtons="false" />

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
                    <h3 class="text-lg font-medium text-red-800 mb-2">{{ __('edit_review.delete_title') }}</h3>
                    <p class="text-red-700 mb-4">
                        {{ __('edit_review.delete_warning') }}
                    </p>
                    <form action="{{ route('patient.appointments.review.destroy', $appointment->id) }}" method="POST"
                        id="delete-review-form">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="confirmDelete()"
                            class="px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                            {{ __('edit_review.btn_delete') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Inject Translation ke JS
                const messages = {
                    ratingRequired: "{{ __('edit_review.error_rating_required') }}",
                    charLimit: "{{ __('edit_review.error_char_limit') }}",
                    updating: "{{ __('edit_review.btn_updating') }}",
                    confirmDelete: "{{ __('edit_review.confirm_delete_msg') }}"
                };

                const reviewTextarea = document.getElementById('review');
                const charCount = document.getElementById('char-count');
                const form = document.querySelector('.review-form');
                const submitBtn = document.getElementById('submit-btn');
                const submitText = document.getElementById('submit-text');
                const submitSpinner = document.getElementById('submit-spinner');

                // Initialize
                if (reviewTextarea) {
                    updateCharCount();
                    reviewTextarea.addEventListener('input', updateCharCount);
                }

                // Form submission
                if (form) {
                    form.addEventListener('submit', function(e) {
                        const ratingInput = document.querySelector('input[name="rating"]');
                        let ratingValue = 0;

                        // Logic pengecekan rating (sama seperti sebelumnya)
                        if (ratingInput) {
                            ratingValue = ratingInput.value;
                        } else {
                            const checkedRadio = document.querySelector('input[name="rating"]:checked');
                            if (checkedRadio) ratingValue = checkedRadio.value;
                        }

                        if (!ratingValue || parseInt(ratingValue) === 0) {
                            e.preventDefault();
                            showSnackbar(messages.ratingRequired, 'error');
                            return;
                        }

                        if (reviewTextarea.value.length > 1000) {
                            e.preventDefault();
                            showSnackbar(messages.charLimit, 'error');
                            return;
                        }

                        // Show loading state
                        submitText.textContent = messages.updating;
                        submitSpinner.classList.remove('hidden');
                        submitBtn.disabled = true;
                    });
                }

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
                        detail: {
                            message,
                            type
                        }
                    }));
                }

                // Fungsi Delete Global
                window.confirmDelete = function() {
                    if (confirm(messages.confirmDelete)) {
                        document.getElementById('delete-review-form').submit();
                    }
                }
            });
        </script>
    @endpush
@endsection
