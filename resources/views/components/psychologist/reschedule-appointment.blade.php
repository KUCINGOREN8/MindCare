@props([
    'appointments' => [],
])

<div class="bg-white p-6 flex flex-col gap-6 rounded-md border-grey-border border">
    <div class="flex flex-1 items-start justify-between">
        <h3 class="font-bold">{{ __('psychologist_dashboard.reschedule_title') }}</h3>
        @if ($appointments->count() > 0)
            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-bold rounded-full">
                {{ __('psychologist_dashboard.pending_count', ['count' => $appointments->count()]) }}
            </span>
        @endif
    </div>

    @forelse($appointments as $appointment)
        @php
            // GUNAKAN translatedFormat AGAR TANGGAL MENYESUAIKAN BAHASA (12 Dec -> 12 Des)
            $originalDate = \Carbon\Carbon::parse($appointment->date)->translatedFormat('d M Y');
            $originalTime = \Carbon\Carbon::parse($appointment->start_time)->format('H:i');

            $requestedDate = $appointment->reschedule_date
                ? \Carbon\Carbon::parse($appointment->reschedule_date)->translatedFormat('d M Y')
                : __('psychologist_dashboard.not_specified');

            $requestedTime = $appointment->reschedule_time
                ? \Carbon\Carbon::parse($appointment->reschedule_time)->format('H:i')
                : __('psychologist_dashboard.not_specified');
        @endphp

        <div class="bg-yellow-50 p-6 rounded-2xl border border-yellow-200">
            <div class="flex items-center gap-4 mb-4">
                <div
                    class="w-12 h-12 rounded-full bg-yellow-100 border-2 border-yellow-300 flex items-center justify-center text-yellow-600 font-bold">
                    {{ substr($appointment->user->full_name ?? 'P', 0, 1) }}
                </div>
                <div>
                    <h4 class="font-bold text-gray-900">
                        {{ $appointment->user->full_name ?? __('psychologist_dashboard.unknown_patient') }}</h4>
                    <p class="text-sm text-gray-600">
                        {{ __('psychologist_dashboard.session_id') }}: #{{ $appointment->id }}
                        @if ($appointment->user->date_of_birth)
                            • {{ \Carbon\Carbon::parse($appointment->user->date_of_birth)->age }}
                            {{ __('psychologist_dashboard.years_old') }}
                        @endif
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                {{-- Current Schedule --}}
                <div class="bg-white p-4 rounded-xl border border-gray-200">
                    <p class="text-xs text-gray-500 mb-2 font-medium">
                        {{ __('psychologist_dashboard.label_current_schedule') }}</p>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="text-sm font-medium">{{ $originalDate }}, {{ $originalTime }}</span>
                    </div>
                </div>

                {{-- Requested Schedule --}}
                <div class="bg-yellow-50 p-4 rounded-xl border border-yellow-300">
                    <p class="text-xs text-yellow-600 mb-2 font-medium">
                        {{ __('psychologist_dashboard.label_requested_time') }}</p>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="text-sm font-medium text-yellow-700">{{ $requestedDate }},
                            {{ $requestedTime }}</span>
                    </div>
                </div>
            </div>

            {{-- Reason --}}
            @if ($appointment->reschedule_reason)
                <div class="mb-4">
                    <p class="text-xs text-gray-500 mb-1 font-medium">
                        {{ __('psychologist_dashboard.label_patient_reason') }}</p>
                    <p class="text-sm text-gray-700 bg-white p-3 rounded-lg border border-gray-200 italic">
                        "{{ $appointment->reschedule_reason }}"
                    </p>
                </div>
            @endif

            {{-- Status Info --}}
            <div class="text-xs text-gray-500 bg-white p-3 rounded-lg border border-gray-200">
                ⚠️ <span class="font-medium">{{ __('psychologist_dashboard.action_required') }}</span>
                {{ __('psychologist_dashboard.action_desc') }}
            </div>
        </div>
    @empty
        <div class="bg-white p-6 text-center rounded-md border-grey-border border flex flex-col gap-6">
            <p class="text-gray-500">{{ __('psychologist_dashboard.empty_reschedule') }}</p>
            <p class="text-sm text-gray-400">{{ __('psychologist_dashboard.empty_reschedule_desc') }}</p>
        </div>
    @endforelse
</div>
