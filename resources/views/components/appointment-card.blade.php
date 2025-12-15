@props([
    'appointment'
])

@if($appointment)
    @php
        $isPendingPayment = $appointment->status === 'pending_payment';

        $cardClass = 'bg-white p-6 flex flex-col gap-4 rounded-md border border-grey-border';
        if ($isPendingPayment) {
            $cardClass .= ' cursor-pointer hover:shadow-md transition-shadow';
        }
    @endphp

    <div
        class="{{ $cardClass }}"
        @if($isPendingPayment)
            onclick="window.location.href='{{ route('patient.appointments.payment', $appointment->id) }}'"
        @endif
    >
        //kepala yee
        {{-- Header --}}
        <div class="flex items-center justify-between gap-3">
            <div class="flex flex-col gap-0">
                <h4 class="font-bold text-gray-900">
                    {{ $appointment->psychologist->user->full_name ?? 'Psychologist' }}
                </h4>

                <p class="text-sm text-gray-600">
                    {{ $appointment->psychologist->specialization
                        ?? $appointment->psychologist->title
                        ?? 'Specialization'
                    }}
                </p>
            </div>

            {{-- Status --}}
            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide
                {{
                    $appointment->status === 'confirmed' ? 'bg-blue-100 text-blue-700'
                    : ($appointment->status === 'completed' ? 'bg-green-100 text-green-700'
                    : ($appointment->status === 'pending_payment' ? 'bg-yellow-100 text-yellow-700'
                    : 'bg-gray-100 text-gray-700'))
                }}">
                {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
            </span>
        </div>

        {{-- Date & Time --}}
        <div class="flex items-center gap-2">
            @php
                $icon = file_get_contents(public_path('assets/icons/calendar.svg'));
                echo str_replace('<svg ', '<svg class="text-gray-500" fill="currentColor" ', $icon);
            @endphp

            <p class="text-sm text-gray-600">
                {{ \Carbon\Carbon::parse($appointment->date)->format('d M Y') }} •
                {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}
                -
                {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}
            </p>
        </div>

        {{-- Action Buttons --}}
        <div class="flex gap-2">

            {{-- Join Session --}}
            <x-appointment-button
                text="Join Session"
                :active="$appointment->is_session_available"
                route="{{ route('patient.appointments.chat.session', $appointment->id) }}"
            />

            {{-- Reschedule (OPEN MODAL, NOT ROUTE) --}}
            @if($appointment->status === 'confirmed' && $appointment->is_upcoming)
            <button
                type="button"
                onclick="openRescheduleModal(
                    {{ $appointment->id }},
                    '{{ $appointment->date }}',
                    '{{ $appointment->start_time }}'
                )"
                class="px-4 py-2 text-sm border rounded-md hover:bg-gray-50"
            >
                Reschedule
            </button>
            @endif

        </div>
    </div>
@endif
