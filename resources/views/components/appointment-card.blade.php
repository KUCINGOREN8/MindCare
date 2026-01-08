@props([
    'appointment' => null,
])

@if ($appointment)
    @php
        $isPendingPayment = $appointment->status === 'pending_payment';
        $canReschedule = $appointment->can_reschedule;

        $rescheduleData = [
            'id' => $appointment->id,
            'psychologist_id' => $appointment->psychologist->id ?? null,
            'date' => $appointment->date ?? '',
            'start_time' => $appointment->start_time ?? '',
            'end_time' => $appointment->end_time ?? '',
            'psychologist_name' => $appointment->psychologist->user->full_name ?? 'Psychologist',
            'psychologist_title' => $appointment->psychologist->title ?? '',
            'status' => $appointment->status ?? 'confirmed',
        ];

        $rescheduleDataJson = json_encode($rescheduleData);
    @endphp

    <div class="bg-white p-4 sm:p-6 flex flex-col gap-4 rounded-md border border-grey-border transition-all duration-200 w-full min-w-0 overflow-hidden {{ $isPendingPayment ? 'cursor-pointer hover:shadow-md transition-shadow' : '' }}"
        @if ($isPendingPayment) onclick="window.location.href='{{ route('patient.appointments.payment', $appointment->id) }}'" @endif>

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 min-w-0">
            <div class="flex flex-col gap-0.5 sm:gap-0 min-w-0">
                <h4 class="font-bold text-gray-900 text-sm sm:text-base break-words leading-snug">
                    {{ $appointment->psychologist->user->full_name ?? 'Psychologist' }}
                </h4>
                <p class="text-xs sm:text-sm text-gray-600 break-words leading-snug">
                    {{ $appointment->psychologist->specialization ?? ($appointment->psychologist->title ?? 'Specialization') }}
                </p>
            </div>

            {{-- Status --}}
            <span
                class="self-start sm:self-center px-2.5 py-1 rounded-full text-[10px] sm:text-xs font-bold uppercase tracking-wide whitespace-nowrap shrink-0 max-w-full
                {{ $appointment->status === 'confirmed'
                    ? 'bg-blue-100 text-blue-700'
                    : ($appointment->status === 'completed'
                        ? 'bg-green-100 text-green-700'
                        : ($appointment->status === 'pending_payment'
                            ? 'bg-yellow-100 text-yellow-700'
                            : 'bg-gray-100 text-gray-700')) }}">
                {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
            </span>
        </div>

        {{-- Date & Time --}}
        <div class="flex items-start gap-2 min-w-0">
            @php
                $icon = file_get_contents(public_path('assets/icons/calendar.svg'));
                echo str_replace(
                    '<svg ',
                    '<svg class="text-gray-500 w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" fill="currentColor" ',
                    $icon,
                );
            @endphp
            <p class="text-xs sm:text-sm text-gray-600 break-words leading-snug">
                {{ \Carbon\Carbon::parse($appointment->date)->format('d M Y') }} •
                {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}
                -
                {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}
            </p>
        </div>

        {{-- Action Buttons --}}
        <div class="flex flex-col-reverse sm:flex-row gap-2 sm:gap-3 mt-1 w-full min-w-0">
            {{-- Join Session --}}
            @if ($appointment->is_session_available)
                <x-appointment-button text="Join Session" :active="true"
                    route="{{ route('patient.appointments.chat.session', $appointment->id) }}"
                    class="w-full sm:w-auto justify-center text-center text-xs sm:text-sm py-2" />
            @endif

            {{-- Reschedule Button --}}
            @if ($canReschedule)
                <button type="button"
                    class="w-full sm:w-auto px-4 py-2 text-xs sm:text-sm border border-gray-300 text-center whitespace-nowrap text-gray-700 rounded-md flex items-center justify-center">
                    Reschedule
                </button>
            @endif
        </div>
    </div>
@endif
