@props([
    'appointment' => null,
    'name' => '',
    'specialization' => '',
    'time' => '',
    'joinRoute' => '#',
    'rescheduleRoute' => '#',
    'isSessionAvailable' => false
])

@if($appointment)
    @php
        $isPendingPayment = $appointment->status === 'pending_payment';
        $cardClass = 'p-4 flex flex-col gap-4 rounded-md border border-grey-border bg-white';
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
        <div class="flex items-center justify-between gap-3">
            <div class="flex flex-col gap-0">
                <h4 class="font-bold text-gray-900">{{ $appointment->psychologist->user->full_name ?? $appointment->with ?? 'Psychologist' }}</h4>
                <p class="text-gray-600 text-sm">{{ $appointment->psychologist->specialization ?? $appointment->psychologist->title ?? 'Specialization' }}</p>
            </div>
            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide 
            {{ $appointment->status === 'confirmed' ? 'bg-blue-100 text-blue-700'
            : ($appointment->status === 'completed' ? 'bg-green-100 text-green-700' 
            : ($appointment->status === 'pending' ? 'bg-yellow-100 text-yellow-700' 
            : 'bg-gray-100 text-gray-700')) }}">
                {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
            </span>
        </div>

        <div class="flex items-center gap-2">
            @php
                $icon = file_get_contents(public_path('assets/icons/calendar.svg'));
                echo str_replace('<svg ', '<svg class="text-gray-500" fill="currentColor" ', $icon);
            @endphp
            <p class="text-gray-600 text-sm">
                {{ \Carbon\Carbon::parse($appointment->date)->format('d M Y') }} •
                {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}
            </p>
        </div>

        <div class="flex gap-2">
            <x-appointment-button
                text="Join Session"
                active="{{ $appointment->is_session_available }}"
                route="{{ route('patient.appointments.chat.session', $appointment->id) }}"
            />
            <x-appointment-button
                text="Reschedule"
                secondary="true"
                route="{{ route('patient.appointments.reschedule', $appointment->id) }}"
            />
        </div>
    </div>
@endif
