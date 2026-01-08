@extends('layouts.dashboard')

@section('title')
    {{ __('client_details.page_title') }} - {{ $client->full_name }}
@endsection

@section('content')
    <div class="flex flex-col flex-1 gap-6 min-w-0 h-full overflow-y-auto pr-2 pb-20 scroll-smooth">
        <div class="flex items-center justify-between bg-white p-6 sm:p-6 px-4 rounded-md border-grey-border border">
            <div class="flex items-center gap-4">
                <a href="{{ route('psychologist.clients') }}" class="text-gray-500 hover:text-primary"
                    title="{{ __('client_details.back_to_clients') }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-primary font-bold text-lg">{{ __('client_details.page_title') }}</h1>
                    <h5 class="text-captiondark text-sm">{{ __('client_details.subtitle', ['name' => $client->full_name]) }}
                    </h5>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="bg-gradient-to-br from-primary/10 via-blue-50 to-purple-50 pt-8 pb-6 px-6">
                        <div class="text-center">
                            <div class="inline-block relative">
                                <div
                                    class="absolute inset-0 bg-gradient-to-br from-primary/20 to-purple-400/20 rounded-full blur-xl">
                                </div>
                                <img src="{{ $client->photo_url }}" alt="{{ $client->full_name }}"
                                    class="relative w-28 h-28 rounded-full object-cover border-4 border-white shadow-lg">
                            </div>
                            <h2 class="text-xl font-bold mt-4 text-gray-800">{{ $client->full_name }}</h2>
                            <div
                                class="inline-flex items-center gap-1.5 mt-2 px-3 py-1 bg-white/80 backdrop-blur-sm rounded-full text-sm text-gray-600">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ __('client_details.client_since', ['date' => $client->created_at->translatedFormat('M Y')]) }}
                            </div>
                        </div>
                    </div>

                    <div class="p-6 sm:p-6 px-4 space-y-6">
                        <div>
                            <div class="flex items-center gap-2 mb-3">
                                <div class="p-1.5 bg-blue-100 rounded-lg">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <h4 class="font-semibold text-gray-800">{{ __('client_details.personal_info') }}</h4>
                            </div>
                            <div class="space-y-3 pl-0.5">
                                {{-- EMAIL --}}
                                <div class="flex items-start gap-3 group">
                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 group-hover:text-primary transition-colors"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-xs text-gray-500 mb-0.5">{{ __('client_details.label_email') }}</p>
                                        <p class="text-sm text-gray-800 font-medium">{{ $client->email }}</p>
                                    </div>
                                </div>

                                {{-- DOB --}}
                                <div class="flex items-start gap-3 group">
                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 group-hover:text-primary transition-colors"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-xs text-gray-500 mb-0.5">{{ __('client_details.label_dob') }}</p>
                                        <p class="text-sm text-gray-800 font-medium">
                                            {{ $client->date_of_birth->translatedFormat('d M Y') }}
                                            <span class="text-gray-500">({{ $client->date_of_birth->age }}
                                                {{ __('client_details.age_years') }})</span>
                                        </p>
                                    </div>
                                </div>

                                {{-- GENDER --}}
                                <div class="flex items-start gap-3 group">
                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 group-hover:text-primary transition-colors"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-xs text-gray-500 mb-0.5">{{ __('client_details.label_gender') }}</p>
                                        <p class="text-sm text-gray-800 font-medium">
                                            {{-- Translate Gender --}}
                                            {{ __('client_details.gender_' . strtolower($client->gender)) }}
                                        </p>
                                    </div>
                                </div>

                                {{-- LANGUAGE --}}
                                <div class="flex items-start gap-3 group">
                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 group-hover:text-primary transition-colors"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-xs text-gray-500 mb-0.5">{{ __('client_details.label_language') }}
                                        </p>
                                        <p class="text-sm text-gray-800 font-medium">
                                            {{ $client->preferred_language == 'en' ? __('client_details.lang_english') : __('client_details.lang_indonesian') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-gray-100"></div>

                        {{-- Session Overview --}}
                        <div>
                            <div class="flex items-center gap-2 mb-3">
                                <div class="p-1.5 bg-purple-100 rounded-lg">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <h4 class="font-semibold text-gray-800">{{ __('client_details.session_overview') }}</h4>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                {{-- Completed --}}
                                <div
                                    class="relative overflow-hidden bg-gradient-to-br from-blue-50 to-blue-100/50 p-4 rounded-xl border border-blue-200/50 hover:shadow-md transition-all duration-200 group">
                                    <div
                                        class="absolute top-0 right-0 w-16 h-16 bg-blue-200/30 rounded-full -mr-8 -mt-8 group-hover:scale-110 transition-transform">
                                    </div>
                                    <div class="relative">
                                        <p class="text-3xl font-bold text-blue-700 mb-1">
                                            {{ $appointments->where('status', 'completed')->count() }}</p>
                                        <p class="text-xs text-blue-600 font-medium">
                                            {{ __('client_details.stat_completed') }}</p>
                                    </div>
                                </div>
                                {{-- Upcoming --}}
                                <div
                                    class="relative overflow-hidden bg-gradient-to-br from-green-50 to-green-100/50 p-4 rounded-xl border border-green-200/50 hover:shadow-md transition-all duration-200 group">
                                    <div
                                        class="absolute top-0 right-0 w-16 h-16 bg-green-200/30 rounded-full -mr-8 -mt-8 group-hover:scale-110 transition-transform">
                                    </div>
                                    <div class="relative">
                                        <p class="text-3xl font-bold text-green-700 mb-1">
                                            {{ $appointments->where('status', 'confirmed')->count() }}</p>
                                        <p class="text-xs text-green-600 font-medium">
                                            {{ __('client_details.stat_upcoming') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="p-6 sm:p-6 px-4 border-b border-gray-100 bg-gradient-to-r from-white to-gray-50">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-bold text-gray-800 text-lg flex items-center gap-2">
                                    <div class="p-1.5 bg-primary/10 rounded-lg">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    {{ __('client_details.history_title') }}
                                </h3>
                                <p class="text-gray-600 text-sm mt-1">
                                    {{ __('client_details.history_subtitle', ['name' => $client->full_name]) }}</p>
                            </div>
                            @if ($appointments->count() > 0)
                                <span class="px-3 py-1.5 bg-primary/10 text-primary text-xs font-bold rounded-full">
                                    {{ __('client_details.session_count', ['count' => $appointments->count()]) }}
                                </span>
                            @endif
                        </div>
                    </div>

                    @if ($appointments->count() > 0)
                        <div class="divide-y divide-gray-100">
                            @foreach ($appointments as $appointment)
                                <div
                                    class="p-6 sm:p-6 px-4 hover:bg-gradient-to-r hover:from-gray-50 hover:to-blue-50/30 transition-all duration-200 group">
                                    <div class="flex flex-col sm:flex-row items-start gap-4 sm:gap-5">
                                        <div class="flex-shrink-0 w-14 sm:w-[70px]">
                                            <div
                                                class="text-center bg-gradient-to-br from-primary/10 to-blue-100/50 rounded-xl p-3 border border-primary/20 w-14 sm:w-[70px] group-hover:shadow-md group-hover:scale-105 transition-all duration-200">
                                                <div class="text-2xl font-bold text-primary">
                                                    {{ \Carbon\Carbon::parse($appointment->date)->format('d') }}
                                                </div>
                                                <div class="text-xs text-primary/80 font-semibold uppercase tracking-wide">
                                                    {{ \Carbon\Carbon::parse($appointment->date)->translatedFormat('M') }}
                                                </div>
                                                <div class="text-xs text-gray-500 mt-0.5">
                                                    {{ \Carbon\Carbon::parse($appointment->date)->format('Y') }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <div class="flex flex-col sm:flex-row sm:justify-between gap-2 sm:gap-4 mb-3">
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <svg class="w-4 h-4 text-gray-400" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        <h4 class="font-bold text-gray-900 text-base">
                                                            {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}
                                                            -
                                                            {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}
                                                        </h4>
                                                    </div>
                                                    <div class="flex items-center gap-3 text-sm text-gray-600">
                                                        <span class="flex items-center gap-1.5">
                                                            {{ __('client_details.session_duration') }}
                                                        </span>
                                                        <span class="text-gray-600">-</span>
                                                        <span class="flex items-center gap-1.5 font-medium">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            {{ number_format($appointment->consultation_fee, 0, ',', '.') }}
                                                            IDR
                                                        </span>
                                                    </div>
                                                </div>
                                                <span
                                                    class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap sm:whitespace-normal
                                                    {{ $appointment->status === 'confirmed'
                                                    ? 'bg-blue-100 text-blue-500 border border-blue-200'
                                                    : ($appointment->status === 'completed'
                                                        ? 'bg-green-100 text-green-500 border border-green-200'
                                                        : 'bg-gray-100 text-gray-500 border border-gray-200') }}">
                                                            {{ __('client_details.status_' . $appointment->status) }}
                                                </span>
                                            </div>

                                            @if ($appointment->notes)
                                                <div
                                                    class="mt-3 p-4 bg-gradient-to-br from-blue-50 to-indigo-50/50 rounded-lg border border-blue-200/50 group/notes hover:shadow-sm transition-all duration-200">
                                                    <div class="flex items-center justify-between mb-2">
                                                        <span
                                                            class="text-sm font-semibold text-blue-600 flex items-center gap-1.5">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                            </svg>
                                                            {{ __('client_details.notes_label') }}
                                                        </span>
                                                        <button type="button"
                                                            onclick="editNotes({{ $appointment->id }}, '{{ addslashes($appointment->notes) }}')"
                                                            class="text-xs text-primary hover:text-primary-dark font-medium flex items-center gap-1 opacity-0 group-hover/notes:opacity-100 transition-opacity">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                            </svg>
                                                            {{ __('client_details.btn_edit_notes') }}
                                                        </button>
                                                    </div>
                                                    <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">
                                                        {{ $appointment->notes }}</p>
                                                </div>
                                            @else
                                                <div class="mt-3">
                                                    <button type="button" onclick="addNotes({{ $appointment->id }})"
                                                        class="text-sm text-blue-600 hover:text-primary-dark font-medium flex items-center gap-1.5 px-3 py-2 rounded-lg hover:bg-primary/5 transition-all duration-200">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M12 4v16m8-8H4" />
                                                        </svg>
                                                        {{ __('client_details.btn_add_notes') }}
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-16 text-center">
                            <div
                                class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full mb-4">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-700 mb-2">{{ __('client_details.no_sessions') }}
                            </h4>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div id="notesModal"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg transform transition-all">
            <div class="p-6 sm:p-6 px-4">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2" id="modalTitle">
                        <div class="p-2 bg-primary/10 rounded-lg">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                        <span id="modalTitleText">{{ __('client_details.modal_add_title') }}</span>
                    </h3>
                    <button type="button" onclick="closeNotesModal()"
                        class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg p-1.5 transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form id="notesForm" onsubmit="saveNotes(event)">
                    @csrf
                    <input type="hidden" name="appointment_id" id="modalAppointmentId">

                    <div class="mb-6">
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-2">{{ __('client_details.modal_label') }}</label>
                        <textarea name="notes" id="sessionNotes" rows="6"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200 resize-none"
                            placeholder="{{ __('client_details.modal_placeholder') }}" required></textarea>
                        <p class="mt-2 text-xs text-gray-500">{{ __('client_details.modal_help') }}</p>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeNotesModal()"
                            class="px-5 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-all duration-200">
                            {{ __('client_details.btn_cancel') }}
                        </button>
                        <button type="submit"
                            class="px-5 py-2.5 bg-primary text-white rounded-lg hover:bg-primary-dark font-medium transition-all duration-200 shadow-sm hover:shadow-md flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            {{ __('client_details.btn_save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // TRANSLATION STRINGS FOR JS
        const LANG_DETAILS = {
            addTitle: "{{ __('client_details.modal_add_title') }}",
            editTitle: "{{ __('client_details.modal_edit_title') }}",
            saving: "{{ __('client_details.js_saving') }}",
            failed: "{{ __('client_details.js_failed') }}"
        };

        function addNotes(appointmentId) {
            document.getElementById('modalTitleText').textContent = LANG_DETAILS.addTitle;
            document.getElementById('modalAppointmentId').value = appointmentId;
            document.getElementById('sessionNotes').value = '';
            document.getElementById('notesModal').classList.remove('hidden');
        }

        function editNotes(appointmentId, existingNotes) {
            document.getElementById('modalTitleText').textContent = LANG_DETAILS.editTitle;
            document.getElementById('modalAppointmentId').value = appointmentId;
            document.getElementById('sessionNotes').value = existingNotes;
            document.getElementById('notesModal').classList.remove('hidden');
        }

        function saveNotes(event) {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;

            submitBtn.disabled = true;
            submitBtn.textContent = LANG_DETAILS.saving;

            fetch("{{ route('psychologist.notes.session.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeNotesModal();
                        window.location.reload();
                    } else {
                        alert(LANG_DETAILS.failed + (data.message || 'Unknown error'));
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
        }

        document.getElementById('notesModal').addEventListener('click', function(e) {
            if (e.target.id === 'notesModal') {
                closeNotesModal();
            }
        });
    </script>
@endsection
