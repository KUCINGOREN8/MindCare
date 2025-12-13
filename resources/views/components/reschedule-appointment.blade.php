<div class="bg-white p-6 flex flex-col gap-6 rounded-md border border-grey-border">

    {{-- Header --}}
    <div class="flex items-start justify-between">
        <h3 class="font-bold text-lg">Reschedule Requests</h3>
    </div>

    @forelse($rescheduleRequests as $appointment)
        <div class="bg-white p-6 rounded-2xl border border-gray-100 relative overflow-hidden">

            {{-- Status Badge --}}
            <div class="absolute top-0 right-0 bg-yellow-100 text-yellow-700 text-[10px] font-bold px-3 py-1 rounded-bl-xl">
                PENDING
            </div>

            <div class="flex items-start gap-4">

                {{-- Icon --}}
                <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>

                {{-- Info --}}
                <div class="flex-1">
                    <h4 class="font-bold text-gray-900">
                        {{ $appointment->psychologist?->user?->full_name ?? 'Psychologist' }}
                    </h4>

                    <p class="text-sm text-gray-500 mt-1">
                        Requested schedule:
                        <strong>
                            {{ \Carbon\Carbon::parse($appointment->reschedule_date)->format('d M Y') }}
                            •
                            {{ \Carbon\Carbon::parse($appointment->reschedule_time)->format('H:i') }}
                        </strong>
                    </p>

                    @if($appointment->reschedule_reason)
                        <p class="text-sm text-gray-500 mt-1">
                            Reason: {{ $appointment->reschedule_reason }}
                        </p>
                    @endif

                    {{-- Patient info --}}
                    <p class="text-xs text-yellow-600 mt-3 font-medium">
                        Waiting for psychologist confirmation
                    </p>
                </div>
            </div>
        </div>

    @empty
        <div class="bg-white p-6 text-center rounded-md border border-grey-border">
            <p class="text-gray-500">No reschedule request found</p>
        </div>
    @endforelse

</div>
