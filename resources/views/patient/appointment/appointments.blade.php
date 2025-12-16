@extends('layouts.dashboard')
@section('title', 'Appointments')

@section('content')
    <div class="flex flex-col flex-1 gap-6 min-w-0 h-full overflow-y-auto pr-2 pb-20 scroll-smooth">

        <div class="flex flex-col bg-white p-6 rounded-md border-grey-border border">
            <h1 class="text-[#00C3B3] font-bold text-lg">{{ __('messages.appointment') }}</h1>
            <h5 class="text-captiondark text-sm">{{ __('messages.appointmentdesc') }}.</h5>
        </div>

        {{-- Upcoming Appointment --}}
        @include('components.upcoming-appointment', [
            'upcomingAppointments' => $upcomingAppointments,
            'user' => $user,
            'showSeeAll' => false,
        ])

        {{-- History --}}
        @include('components.history-appointment')

        {{-- Reschedule Function --}}
        <div id="rescheduleModal"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden z-[9999] items-center justify-center p-4 transition-opacity duration-300">
        </div>

    </div>
@endsection

@push('styles')
<style>
    .modal-enter {
        opacity: 0;
        transform: scale(0.95);
    }
    .modal-enter-active {
        opacity: 1;
        transform: scale(1);
        transition: opacity 200ms ease-out, transform 200ms ease-out;
    }
    .modal-exit {
        opacity: 1;
        transform: scale(1);
    }
    .modal-exit-active {
        opacity: 0;
        transform: scale(0.95);
        transition: opacity 200ms ease-in, transform 200ms ease-in;
    }
</style>
@endpush

@push('scripts')
<script>
let currentAppointmentId = null;
let currentPsychologistId = null;

window.openRescheduleModal = function(data) {
    if (typeof data === 'string') {
        try {
            data = JSON.parse(data);
        } catch (e) {
            return;
        }
    }

    if (typeof data !== 'object' || data === null) {
        return;
    }

    currentAppointmentId = data.id;
    currentPsychologistId = data.psychologist_id;

    const modal = document.getElementById('rescheduleModal');

    if (!modal) {
        return;
    }

    const formatDate = (dateStr) => {
        try {
            if (!dateStr) return 'Date not set';
            const date = new Date(dateStr);
            if (isNaN(date.getTime())) return 'Invalid date';

            return date.toLocaleDateString('en-GB', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
        } catch (e) {
            return 'Date error';
        }
    };

    const formatTime = (timeStr) => {
        if (!timeStr) return 'N/A';
        if (timeStr.includes(':')) {
            return timeStr.substring(0, 5);
        }
        return timeStr;
    };

    const formatStatus = (status) => {
        if (!status) return 'Confirmed';
        return status.charAt(0).toUpperCase() + status.slice(1).replace('_', ' ');
    };

    const modalHTML = `
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm w-full max-w-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-[#00C3B3] font-bold text-lg">Reschedule Appointment</h1>
                        <p class="text-captiondark text-sm mt-1">Select new date and time</p>
                    </div>
                    <button type="button" onclick="closeRescheduleModal()" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg p-1 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="bg-gray-50 rounded-lg p-4 mb-6 border border-gray-200">
                    <h3 class="font-medium text-gray-700 mb-2">Current Appointment</h3>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Date & Time</p>
                            <p class="font-semibold">${formatDate(data.date)} at ${formatTime(data.start_time)} - ${formatTime(data.end_time)}</p>
                            <p class="text-sm text-gray-600 mt-1">Psychologist</p>
                            <p class="font-medium">${data.psychologist_name || 'Psychologist'}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Status</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                ${formatStatus(data.status)}
                            </span>
                        </div>
                    </div>
                </div>

                <form id="rescheduleForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <h2 class="font-semibold mb-4 text-lg">Select New Date</h2>
                        <input type="date" id="rescheduleDate" name="reschedule_date"
                               class="border border-gray-300 focus:ring-[#00C3B3] focus:border-[#00C3B3] rounded-lg px-4 py-2.5 w-full"
                               required min="${getTomorrowDate()}"
                               onchange="loadAvailableTimes()">
                    </div>

                    <div id="timeSection" class="mb-6 hidden">
                        <h2 class="font-semibold mb-4 text-lg">Select New Time</h2>
                        <div id="timeLoading" class="hidden text-center py-8">
                            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-[#00C3B3]"></div>
                            <p class="mt-2 text-gray-600">Loading available times...</p>
                        </div>
                        <div id="noTimesMessage" class="hidden text-center py-8">
                            <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded inline-block">
                                <p>No available times for selected date.</p>
                            </div>
                        </div>
                        <div id="timeOptions" class="grid grid-cols-3 gap-3"></div>
                        <input type="hidden" id="selectedTime" name="reschedule_time" required>
                    </div>

                    <div id="rescheduleError" class="hidden mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded">
                        <p id="errorMessage"></p>
                    </div>
                </form>
            </div>

            <div class="flex justify-end gap-3 p-6 border-t border-gray-200">
                <button type="button" onclick="closeRescheduleModal()"
                        class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                    Cancel
                </button>
                <button type="button" onclick="submitReschedule()" id="submitButton"
                        class="px-5 py-2.5 bg-[#00C3B3] text-white rounded-lg hover:bg-[#00C3B3]/90 font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled>
                    Confirm Reschedule
                </button>
            </div>
        </div>
    `;

    modal.innerHTML = modalHTML;

    const form = document.getElementById('rescheduleForm');
    if (form && data.id) {
        form.action = `/patient/appointments/${data.id}/reschedule`;
    }

    setupDateInput();

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';

}

function closeRescheduleModal() {
    const modal = document.getElementById('rescheduleModal');
    if (!modal) return;

    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = 'auto';
    currentAppointmentId = null;
    currentPsychologistId = null;
}

function getTomorrowDate() {
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    return tomorrow.toISOString().split('T')[0];
}

function setupDateInput() {
    const dateInput = document.getElementById('rescheduleDate');
    if (!dateInput) {
        return;
    }

    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    dateInput.min = tomorrow.toISOString().split('T')[0];
    dateInput.value = '';

    const elements = {
        selectedTime: document.getElementById('selectedTime'),
        timeOptions: document.getElementById('timeOptions'),
        noTimesMessage: document.getElementById('noTimesMessage'),
        rescheduleError: document.getElementById('rescheduleError'),
        submitButton: document.getElementById('submitButton'),
        timeSection: document.getElementById('timeSection')
    };

    if (elements.selectedTime) elements.selectedTime.value = '';
    if (elements.timeOptions) elements.timeOptions.innerHTML = '';
    if (elements.noTimesMessage) elements.noTimesMessage.classList.add('hidden');
    if (elements.rescheduleError) elements.rescheduleError.classList.add('hidden');
    if (elements.submitButton) elements.submitButton.disabled = true;
    if (elements.timeSection) elements.timeSection.classList.add('hidden');
}

async function loadAvailableTimes() {
    const dateInput = document.getElementById('rescheduleDate');
    if (!dateInput) {
        return;
    }

    const date = dateInput.value;
    if (!date || !currentAppointmentId) {
        return;
    }

    const elements = {
        timeSection: document.getElementById('timeSection'),
        timeLoading: document.getElementById('timeLoading'),
        timeOptions: document.getElementById('timeOptions'),
        noTimesMessage: document.getElementById('noTimesMessage'),
        submitButton: document.getElementById('submitButton')
    };

    if (elements.timeSection) elements.timeSection.classList.remove('hidden');
    if (elements.timeLoading) elements.timeLoading.classList.remove('hidden');
    if (elements.timeOptions) elements.timeOptions.innerHTML = '';
    if (elements.noTimesMessage) elements.noTimesMessage.classList.add('hidden');
    if (elements.submitButton) elements.submitButton.disabled = true;

    const selectedTime = document.getElementById('selectedTime');
    if (selectedTime) selectedTime.value = '';

    try {
        const url = `/patient/appointments/${currentAppointmentId}/reschedule-times?date=${date}`;
        const response = await fetch(url, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            credentials: 'same-origin'
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const times = await response.json();
        if (times && Array.isArray(times) && times.length > 0 && elements.timeOptions) {
            let html = '';
            times.forEach(time => {
                const safeTime = time.replace(/'/g, "\\'");
                html += `<button type="button" onclick="selectTime('${safeTime}')"
                        class="time-option px-4 py-2 rounded-lg border text-sm transition-all border-gray-300 hover:border-[#00C3B3] hover:bg-[#00C3B3]/10"
                        data-time="${safeTime}">${time}</button>`;
            });
            elements.timeOptions.innerHTML = html;
            if (elements.noTimesMessage) elements.noTimesMessage.classList.add('hidden');
        } else {
            if (elements.timeOptions) elements.timeOptions.innerHTML = '';
            if (elements.noTimesMessage) elements.noTimesMessage.classList.remove('hidden');
        }
    } catch (err) {
        if (elements.timeOptions) elements.timeOptions.innerHTML = '';
        if (elements.noTimesMessage) {
            elements.noTimesMessage.innerHTML = '<p class="text-red-600">Error loading times. Please try again.</p>';
            elements.noTimesMessage.classList.remove('hidden');
        }
    } finally {
        if (elements.timeLoading) elements.timeLoading.classList.add('hidden');
    }
}

function selectTime(time) {
    document.querySelectorAll('.time-option').forEach(btn => {
        btn.classList.remove('bg-[#00C3B3]', 'text-white', 'border-[#00C3B3]');
        btn.classList.add('border-gray-300', 'text-gray-700');
    });
    const selectedBtn = document.querySelector(`[data-time="${time}"]`);

    if (selectedBtn) {
        selectedBtn.classList.remove('border-gray-300', 'text-gray-700');
        selectedBtn.classList.add('bg-[#00C3B3]', 'text-white', 'border-[#00C3B3]');
    }

    const selectedTime = document.getElementById('selectedTime');

    if (selectedTime) {
        selectedTime.value = time;
    }

    const submitButton = document.getElementById('submitButton');

    if (submitButton) {
        submitButton.disabled = false;
    }
}

function submitReschedule() {
    const form = document.getElementById('rescheduleForm');
    const errorEl = document.getElementById('rescheduleError');
    const date = document.getElementById('rescheduleDate')?.value;
    const time = document.getElementById('selectedTime')?.value;

    if (!date || !time) {
        if (errorEl) {
            errorEl.classList.remove('hidden');
            const errorMessage = document.getElementById('errorMessage');
            if (errorMessage) {
                errorMessage.textContent = 'Please select both date and time';
            }
        }
        return;
    }

    if (form) {
        form.submit();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('rescheduleModal');
        if (e.target === modal) {
            closeRescheduleModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeRescheduleModal();
        }
    });
});
</script>
@endpush
