<div class="bg-white p-6 flex flex-col gap-6 rounded-md border-grey-border border">
    <div class="flex flex-1 items-start">
        <h3 class="font-bold">Reschedule Requests</h3>
    </div>

    @forelse($rescheduleRequests ?? [] as $request)
        {{-- Kartu Request Pasien --}}
        <div class="bg-white p-6 rounded-2xl border border-gray-100 relative overflow-hidden">
            {{-- Status request dari Pasien selalu Pending/Menunggu Konfirmasi Psikolog --}}
            <div class="absolute top-0 right-0 bg-yellow-100 text-yellow-700 text-[10px] font-bold px-3 py-1 rounded-bl-xl">AWAITING APPROVAL</div>
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                    {{-- Icon Calendar --}}
                </div>
                <div>
                    <h4 class="font-bold text-gray-900">{{ $request->psychologist->user->full_name ?? 'Psychologist' }}</h4> 
                    
                    <p class="text-sm text-gray-500">Original: {{ \Carbon\Carbon::parse($request->date)->format('D, d M') }} at {{ \Carbon\Carbon::parse($request->start_time)->format('h:i A') }}</p>

                    <p class="text-sm text-gray-500 mt-1">Requested: 
                        <strong>{{ \Carbon\Carbon::parse($request->reschedule_date)->format('D, d M') }} at {{ \Carbon\Carbon::parse($request->reschedule_time)->format('h:i A') }}</strong>
                    </p>
                    
                    @if($request->reschedule_reason)
                        <p class="text-sm text-gray-500 mt-1">Reason: {{ $request->reschedule_reason }}</p>
                    @endif
                </div>
            </div>
            
            {{-- Pasien tidak perlu tombol approve/reject, hanya tombol cancel reschedule jika diizinkan --}}
            
        </div>
    @empty
        <div class="bg-white p-6 text-center rounded-md border-grey-border border flex flex-col gap-6">
            <p class="text-gray-500">No active reschedule request found</p>
        </div>
    @endforelse
</div>