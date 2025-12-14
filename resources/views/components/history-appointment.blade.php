<div class="bg-white p-6 flex flex-col gap-6 rounded-md border-grey-border border">
    <div class="flex flex-1 items-start">
        <h3 class="font-bold">History Appointments</h3>
    </div>

    @forelse($history ?? [] as $item)
        @php
            $isPendingPayment = $item->status === 'pending_payment';
            $payment = \App\Models\Payment::where('paymentable_id', $item->id)
                ->where('paymentable_type', \App\Models\Appointment::class)
                ->first();
            $isPaymentExpired = $payment && $payment->status === 'expired';
            $canMakePayment = $isPendingPayment && $payment && $payment->status === 'pending';
        @endphp

        <div class="bg-white p-4 rounded-xl border border-grey-border hover:shadow-md transition flex items-center justify-between group {{ $isPendingPayment ? 'cursor-pointer' : '' }}"
            @if($isPendingPayment)
                onclick="window.location.href='{{ route('patient.appointments.payment', $item->id) }}'"
            @elseif($isPaymentExpired)
                onclick="showExpiredMessage()"
            @endif
        >
            @php
                $iconBgColor = 'bg-gray-50';
                $iconTextColor = 'text-gray-500';
                $badgeBgColor = 'bg-gray-100';
                $badgeTextColor = 'text-gray-700';
                
                if($item->status === 'completed') {
                    $iconBgColor = 'bg-green-50';
                    $iconTextColor = 'text-green-500';
                    $badgeBgColor = 'bg-green-100';
                    $badgeTextColor = 'text-green-700';
                } elseif($item->status === 'confirmed') {
                    $iconBgColor = 'bg-blue-50';
                    $iconTextColor = 'text-blue-500';
                    $badgeBgColor = 'bg-blue-100';
                    $badgeTextColor = 'text-blue-700';
                } elseif($item->status === 'pending') {
                    $iconBgColor = 'bg-yellow-50';
                    $iconTextColor = 'text-yellow-500';
                    $badgeBgColor = 'bg-yellow-100';
                    $badgeTextColor = 'text-yellow-700';
                } elseif($item->status === 'cancelled') {
                    $iconBgColor = 'bg-red-50';
                    $iconTextColor = 'text-red-500';
                    $badgeBgColor = 'bg-red-100';
                    $badgeTextColor = 'text-red-700';
                } elseif($isPendingPayment) {
                    $iconBgColor = 'bg-purple-50';
                    $iconTextColor = 'text-purple-500';
                }
                
                if($isPaymentExpired) {
                    $badgeBgColor = 'bg-gray-100';
                    $badgeTextColor = 'text-gray-700';
                } elseif($canMakePayment) {
                    $badgeBgColor = 'bg-purple-100';
                    $badgeTextColor = 'text-purple-700';
                }
            @endphp

            <div class="flex flex-col flex-1 gap-5">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full {{ $iconBgColor }} {{ $iconTextColor }} flex items-center justify-center group-hover:bg-green-500 group-hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-gray-800 text-sm">
                            {{ $item->psychologist->user->full_name ?? $item->with ?? 'Psychologist' }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ isset($item->date) ? \Carbon\Carbon::parse($item->date)->format('d F Y') : '-' }} •
                            {{ $item->start_time }} - {{ $item->end_time }}
                            @if($item->psychologist->specialization ?? false)
                                <br><span class="text-gray-400">Specialization: {{ $item->psychologist->specialization }}</span>
                            @endif
                        </p>
                    </div>
                </div>
                
                <div class="">
                    @if ($item->has_been_reviewed)
                        <a href="{{ route('patient.appointments.review.edit', $item->id) }}"class="px-4 py-2 bg-[#00C3B3] hover:bg-[#179990] text-white rounded-md items-center justify-center">
                            Edit Review
                        </a>
                    @else
                        <a href="{{ route('patient.appointments.review.create', $item->id) }}" class="px-4 py-2 bg-[#00C3B3] hover:bg-[#179990] text-white rounded-md items-center justify-center">
                            Give Review
                        </a>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-3">
                <span class="px-3 py-1 rounded-full text-[10px] font-bold {{ $badgeBgColor }} {{ $badgeTextColor }} uppercase tracking-wide">
                    @if($isPaymentExpired)
                        Payment Expired
                    @elseif($canMakePayment)
                        Awaiting Payment
                    @elseif($isPendingPayment)
                        Payment Expired
                    @else
                        {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                    @endif
                </span>

                @if($item->status === 'confirmed')
                    <button 
                        onclick="event.stopPropagation(); openRescheduleModal(
                            '{{ $item->id }}',
                            '{{ $item->date }}',
                            '{{ $item->start_time }}'
                        )"
                        class="px-3 py-1 text-xs rounded bg-yellow-500 text-white hover:bg-yellow-600 transition">
                        Reschedule
                    </button>
                @endif
            </div>

        </div>
    @empty
        <div class="bg-white p-6 text-center rounded-md border-grey-border border flex flex-col gap-6">
            <p class="text-gray-500">No history found</p>
        </div>
    @endforelse
</div>
