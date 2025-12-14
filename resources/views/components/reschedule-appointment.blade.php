<div class="bg-white p-6 flex flex-col gap-6 rounded-md border-grey-border border">
    <div class="flex flex-1 items-start">
        <h3 class="font-bold">Reschedule Requests</h3>
    </div>

    @forelse($rescheduleRequests ?? [] as $request)
        <div class="bg-white p-6 rounded-2xl border border-gray-100 relative overflow-hidden">
            <div class="absolute top-0 right-0 bg-yellow-100 text-yellow-700 text-[10px] font-bold px-3 py-1 rounded-bl-xl">PENDING</div>
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <h4 class="font-bold text-gray-900">{{ $request->psychologist->user->full_name ?? 'Psychologist' }}</h4>
                    <p class="text-sm text-gray-500">Requested: <strong>{{ \Carbon\Carbon::parse($request->reschedule_date)->format('l, h:i A') }}</strong></p>
                    @if($request->reschedule_reason)
                        <p class="text-sm text-gray-500 mt-1">Reason: {{ $request->reschedule_reason }}</p>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white p-6 text-center rounded-md border-grey-border border flex flex-col gap-6">
            <p class="text-gray-500">No reschedule request found</p>
        </div>
    @endforelse
</div>
