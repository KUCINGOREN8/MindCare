@props([
    'appointment' => null,
    'isSessionAvailable' => false
])

@if($appointment)
    @php
        $patientAge = $appointment->user->date_of_birth
            ? \Carbon\Carbon::parse($appointment->user->date_of_birth)->age
            : null;

        $statusColors = [
            'confirmed' => 'bg-blue-100 text-blue-700',
            'completed' => 'bg-green-100 text-green-700',
            'pending_payment' => 'bg-yellow-100 text-yellow-700',
            'pending' => 'bg-yellow-100 text-yellow-700',
            'cancelled' => 'bg-red-100 text-red-700',
        ];
        $statusClass = $statusColors[$appointment->status] ?? 'bg-gray-100 text-gray-700';
    @endphp

    <div class="p-4 flex flex-col gap-4 rounded-md border border-grey-border bg-white hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <img src="{{ $appointment->user->photo_url ?? ($appointment->user->gender == 'female' ? asset('assets/icons/user_female.svg') : asset('assets/icons/user_male.svg')) }}"
                     alt="{{ $appointment->user->full_name }}"
                     class="w-12 h-12 rounded-full object-cover border-2 border-gray-100">
                <div class="flex flex-col gap-0">
                    <h4 class="font-bold text-gray-900">{{ $appointment->user->full_name }}</h4>
                    <p class="text-gray-600 text-sm">
                        @if($patientAge)
                            {{ $appointment->user->gender == 'female' ? 'Female' : 'Male' }}, {{ $patientAge }} years
                        @else
                            {{ $appointment->user->gender == 'female' ? 'Female' : 'Male' }} Patient
                        @endif
                        @if($appointment->job_title)
                            - {{ $appointment->job_title }}
                        @endif
                    </p>
                </div>
            </div>
            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide {{ $statusClass }}">
                {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
            </span>
        </div>

        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="text-gray-600 text-sm">
                {{ \Carbon\Carbon::parse($appointment->date)->format('d M Y') }} •
                {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}
            </p>
        </div>

        <div class="flex gap-2">
            @if($appointment->is_session_available && $appointment->status === 'confirmed')
                <a href="{{ route('psychologist.appointments.chat.session', $appointment->id) }}"
                class="flex-1 px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-dark text-center text-sm font-medium transition">
                    Join Session
                </a>
            @elseif($appointment->status === 'confirmed')
                <button class="flex-1 px-4 py-2 bg-gray-100 text-gray-500 rounded-md text-center text-sm font-medium cursor-not-allowed">
                    @if(now() < $appointment->start_date_time)
                        Starts at {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}
                    @else
                        Session Ended
                    @endif
                </button>
            @endif

            <a href="{{ route('psychologist.clients.details', $appointment->user_id) }}"
            class="flex-1 px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50 text-center text-sm font-medium transition">
                View Profile
            </a>

            <button class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50 text-sm font-medium transition flex items-center justify-center gap-1.5"
                    onclick="openSessionNotesModal({{ $appointment->id }}, '{{ addslashes($appointment->notes ?? '') }}', '{{ $appointment->user->full_name }}', '{{ \Carbon\Carbon::parse($appointment->date)->format('d M Y') }}')"
                    title="Add Session Notes">
                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </button>
        </div>

        @if($appointment->status === 'pending_payment')
            <div class="text-xs text-yellow-600 bg-yellow-50 p-2 rounded border border-yellow-100">
                Waiting for patient's payment. Session will be confirmed after payment.
            </div>
        @endif
    </div>
@endif

<div id="sessionNotesModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg transform transition-all">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2" id="modalTitle">
                    <span id="modalTitleText">Session Notes</span>
                </h3>
                <button type="button" onclick="closeSessionNotesModal()"
                        class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg p-1.5 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="sessionNotesForm" onsubmit="saveSessionNotes(event)">
                @csrf
                <input type="hidden" name="appointment_id" id="sessionAppointmentId">

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Session Notes</label>
                    <textarea name="notes" id="sessionNotesTextarea" rows="6"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200 resize-none"
                            placeholder="Document session details, observations, discussions, interventions used, client responses, homework assigned, and follow-up plans..."
                            required></textarea>
                    <p class="mt-2 text-xs text-gray-500">These notes are specific to this session and help track progress.</p>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeSessionNotesModal()"
                            class="px-5 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-all duration-200">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-5 py-2.5 bg-primary text-white rounded-lg hover:bg-primary-dark font-medium transition-all duration-200 shadow-sm hover:shadow-md flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Save Notes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openSessionNotesModal(appointmentId, existingNotes, patientName, sessionDate) {
    document.getElementById('sessionAppointmentId').value = appointmentId;
    document.getElementById('sessionNotesTextarea').value = existingNotes;
    document.getElementById('modalTitle').textContent = 'Add Session Notes';
    document.getElementById('sessionNotesModal').classList.remove('hidden');
}

function saveSessionNotes(event) {
    event.preventDefault();

    const form = event.target;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;

    submitBtn.disabled = true;
    submitBtn.textContent = 'Saving...';

    fetch("{{ route('psychologist.notes.session.store') }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            appointment_id: formData.get('appointment_id'),
            notes: formData.get('notes')
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeSessionNotesModal();
            window.location.reload();
        } else {
            alert(data.message || 'Failed to save notes');
        }
    })
    .catch(err => {
        console.error('Error:', err);
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    });
}

function closeSessionNotesModal() {
    document.getElementById('sessionNotesModal').classList.add('hidden');
    document.getElementById('sessionNotesForm').reset();
}

document.getElementById('sessionNotesModal').addEventListener('click', function(e) {
    if (e.target.id === 'sessionNotesModal') {
        closeSessionNotesModal();
    }
});
</script>
