@extends('layouts.dashboard')

@section('title')
My Clients
@endsection

@section('content')
<div class="flex flex-col flex-1 gap-6 min-w-0 h-full overflow-y-auto pr-2 pb-20 scroll-smooth">
    <!-- Header -->
    <div class="flex flex-col bg-white p-6 rounded-md border-grey-border border">
        <h1 class="text-primary font-bold text-lg">My Clients</h1>
        <h5 class="text-captiondark text-sm">Manage your clients and their progress</h5>
    </div>

    <!--Header Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white p-6 rounded-md border-grey-border border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-captiondark text-sm">Total Clients</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $clients->count() }}</p>
                </div>
                <div class="p-3 bg-blue-50 rounded-full">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-md border-grey-border border">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-captiondark text-sm">Active Sessions</p>
                    <p class="text-2xl font-bold text-gray-800">
                        {{ $clients->sum(function($client) {
                            return $client->appointments->where('status', 'confirmed')->count();
                        }) }}
                    </p>
                </div>
                <div class="p-3 bg-green-50 rounded-full">
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- All Clients -->
    <div class="bg-white rounded-md border-grey-border border overflow-hidden">
        <div class="p-6 border-b border-grey-border">
            <h3 class="font-bold text-gray-800">All Clients</h3>
            <p class="text-captiondark text-sm">Click on a client to view details and session history</p>
        </div>

        @if($clients->count() > 0)
            <div class="divide-y divide-gray-100">
                @foreach($clients as $client)
                @php
                    $activeAppointments = $client->appointments->where('status', 'confirmed');
                    $pendingPaymentAppointments = $client->appointments->where('status', 'pending_payment');
                    $pendingAppointments = $client->appointments->where('status', 'pending');
                    $completedAppointments = $client->appointments->where('status', 'completed');
                    $totalRelevantAppointments = $client->appointments->whereIn('status', ['confirmed', 'completed', 'pending_payment', 'pending'])->count();
                @endphp

                <div class="p-6 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <!-- Profile -->
                            <div class="relative">
                                <img src="{{ $client->photo_url ? asset($client->photo_url) : ($client->gender=="female" ? asset('assets/icons/user_female.svg') : asset('assets/icons/user_male.svg')) }}"
                                     alt="{{ $client->full_name }}"
                                     class="w-12 h-12 rounded-full object-cover">
                            </div>

                            <!-- Information -->
                            <div>
                                <h4 class="font-bold text-gray-900">{{ $client->full_name }}</h4>
                                <div class="flex items-center gap-3 mt-1">
                                    <span class="text-sm text-gray-600">
                                        {{ $totalRelevantAppointments }} session(s)
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-6">
                            <!-- Next Session -->
                            @php
                                $nextAppointment = $client->appointments
                                    ->where('status', 'confirmed')
                                    ->where('date', '>=', now()->format('Y-m-d'))
                                    ->sortBy('date')
                                    ->first();
                            @endphp

                            @if($nextAppointment)
                            <div class="text-right">
                                <p class="text-sm text-gray-500">Next Session</p>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($nextAppointment->date)->format('M d') }}
                                    - {{ \Carbon\Carbon::parse($nextAppointment->start_time)->format('H:i') }}
                                </p>
                            </div>
                            @endif

                            <!-- Buttons -->
                            <div class="flex items-center gap-2">
                                <!-- Notes Button -->
                                <button type="button"
                                        class="p-2 text-gray-500 hover:text-primary hover:bg-gray-100 rounded-full transition-colors notes-button"
                                        title="Add General Notes"
                                        data-client-id="{{ $client->id }}"
                                        data-client-name="{{ $client->full_name }}"
                                        onclick="openNotesModal(this)">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>

                                <!-- Details Button -->
                                <button class="p-2 text-gray-500 hover:text-primary hover:bg-gray-100 rounded-full transition-colors"
                                        title="View Client Details"
                                        onclick="window.location.href='{{ route('psychologist.clients.details', $client->id) }}'">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Last Session Info -->
                    @php
                        $lastAppointment = $client->appointments
                            ->whereIn('status', ['confirmed', 'completed', 'pending_payment', 'pending'])
                            ->sortByDesc('date')
                            ->first();
                    @endphp
                    @if($lastAppointment)
                    <div class="mt-4 pl-16 text-sm text-gray-500">
                        Last session:
                        <span class="text-gray-700">
                            {{ \Carbon\Carbon::parse($lastAppointment->date)->format('M d, Y') }}
                            ({{ ucfirst(str_replace('_', ' ', $lastAppointment->status)) }})
                        </span>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="p-12 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                <h4 class="text-lg font-medium text-gray-700 mb-2">No clients yet</h4>
                <p class="text-gray-500 mb-6">Your clients will appear here once they book appointments with you.</p>
            </div>
        @endif
    </div>
</div>

<!-- Notes Modal  -->
<div id="notesModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg transform transition-all">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2" id="modalTitle">
                    <span id="modalTitleText">General Notes</span>
                </h3>
                <button type="button" onclick="closeNotesModal()"
                        class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg p-1.5 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="notesForm" onsubmit="saveNotes(event)">
                @csrf
                <input type="hidden" name="client_id" id="modalClientId">

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">General Notes</label>
                    <textarea name="general_notes" id="sessionNotes" rows="6"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200 resize-none"
                            placeholder="Document general observations, progress, key insights, treatment plans, and recommendations..."
                            required></textarea>
                    <p class="mt-2 text-xs text-gray-500">Your notes are confidential and help track client progress over time.</p>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeNotesModal()"
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
let currentNotesData = null;

function openNotesModal(button) {
    const clientId = button.getAttribute('data-client-id');
    const clientName = button.getAttribute('data-client-name');

    document.getElementById('modalTitleText').textContent = `Notes for ${clientName}`;
    document.getElementById('modalClientId').value = clientId;
    document.getElementById('sessionNotes').value = '';

    fetch(`/psychologist/clients/${clientId}/general-notes`)
        .then(response => {
            if (!response.ok) {
                console.log('No existing notes found');
                return { general_notes: '' };
            }
            return response.json();
        })
        .then(data => {
            if (data.general_notes) {
                document.getElementById('sessionNotes').value = data.general_notes;
            }
        })
        .catch(error => {
            console.log('Error fetching notes:', error);
        });

    document.getElementById('notesModal').classList.remove('hidden');
}

function saveNotes(event) {
    event.preventDefault();

    const form = event.target;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;

    submitBtn.disabled = true;
    submitBtn.textContent = 'Saving...';

    fetch("{{ route('psychologist.notes.general.store') }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response...');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            closeNotesModal();
            window.location.reload();
        } else {
            alert('Failed to save notes: ' + (data.message || 'Unknown error'));
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

function closeNotesModal() {
    document.getElementById('notesModal').classList.add('hidden');
    document.getElementById('notesForm').reset();
    currentNotesData = null;
}

document.getElementById('notesModal').addEventListener('click', function(e) {
    if (e.target.id === 'notesModal') {
        closeNotesModal();
    }
});
</script>
@endsection
