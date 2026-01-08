<div class="bg-white p-4 sm:p-6 flex flex-col gap-4 sm:gap-6 rounded-md border-grey-border border overflow-x-hidden">
    <div class="flex items-start">
        <h3 class="font-bold text-base sm:text-lg">{{ __('messages.historyappt') }}</h3>
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

        <div class="bg-white p-4 rounded-xl border border-grey-border hover:shadow-md transition flex flex-col sm:flex-row sm:items-center justify-between gap-4 group {{ $isPendingPayment ? 'cursor-pointer' : '' }}"
            @if ($isPendingPayment) onclick="window.location.href='{{ route('patient.appointments.payment', $item->id) }}'"
            @elseif($isPaymentExpired)
                onclick="showExpiredMessage()" @endif>
            @php
                $iconBgColor = 'bg-gray-50';
                $iconTextColor = 'text-gray-500';
                $badgeBgColor = 'bg-gray-100';
                $badgeTextColor = 'text-gray-700';

                if ($item->status === 'completed') {
                    $iconBgColor = 'bg-green-50';
                    $iconTextColor = 'text-green-500';
                    $badgeBgColor = 'bg-green-100';
                    $badgeTextColor = 'text-green-700';
                } elseif ($item->status === 'confirmed') {
                    $iconBgColor = 'bg-blue-50';
                    $iconTextColor = 'text-blue-500';
                    $badgeBgColor = 'bg-blue-100';
                    $badgeTextColor = 'text-blue-700';
                } elseif ($item->status === 'pending') {
                    $iconBgColor = 'bg-yellow-50';
                    $iconTextColor = 'text-yellow-500';
                    $badgeBgColor = 'bg-yellow-100';
                    $badgeTextColor = 'text-yellow-700';
                } elseif ($item->status === 'cancelled') {
                    $iconBgColor = 'bg-red-50';
                    $iconTextColor = 'text-red-500';
                    $badgeBgColor = 'bg-red-100';
                    $badgeTextColor = 'text-red-700';
                } elseif ($isPendingPayment) {
                    $iconBgColor = 'bg-purple-50';
                    $iconTextColor = 'text-purple-500';
                }

                if ($isPaymentExpired) {
                    $badgeBgColor = 'bg-gray-100';
                    $badgeTextColor = 'text-gray-700';
                } elseif ($canMakePayment) {
                    $badgeBgColor = 'bg-purple-100';
                    $badgeTextColor = 'text-purple-700';
                }
            @endphp

            <div class="flex flex-1 gap-3 sm:gap-4 items-start w-full min-w-0">
                <div
                    class="w-10 h-10 rounded-full {{ $iconBgColor }} {{ $iconTextColor }} flex-shrink-0 flex items-center justify-center group-hover:bg-green-500 group-hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                        </path>
                    </svg>
                </div>
                <div class="flex flex-col min-w-0 w-full">
                    <p class="font-bold text-gray-800 text-sm sm:text-base break-words">
                        {{ $item->psychologist->user->full_name ?? ($item->with ?? 'Psychologist') }}
                    </p>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">
                        {{ isset($item->date) ? \Carbon\Carbon::parse($item->date)->format('d F Y') : '-' }} •
                        {{ $item->start_time }} - {{ $item->end_time }}
                        @if ($item->psychologist->specialization ?? false)
                            <br><span
                                class="text-xs sm:text-sm text-gray-400 mt-1 break-words text-wrap leading-tight">Specialization:
                                {{ $item->psychologist->specialization }}</span>
                        @endif
                    </p>
                </div>
            </div>

            <div class="flex flex-col gap-3 w-full sm:w-auto sm:items-end mt-2 sm:mt-0 sm:pl-4">
                <div class="flex items-center justify-between sm:justify-end gap-2 w-full sm:w-auto">
                    <span
                        class="px-2.5 py-1 rounded-full text-[10px] sm:text-xs font-bold {{ $badgeBgColor }} {{ $badgeTextColor }} uppercase tracking-wide whitespace-nowrap">
                        @if ($isPaymentExpired)
                            {{ __('messages.paymentexpired') }}
                        @elseif($canMakePayment)
                            {{ __('messages.makepayment') }}
                        @elseif($isPendingPayment)
                            {{ __('messages.paymentexpired') }}
                        @else
                            {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                        @endif
                    </span>

                    @if ($item->status === 'confirmed')
                        <button
                            onclick="event.stopPropagation(); openRescheduleModal(
                            '{{ $item->id }}',
                            '{{ $item->date }}',
                            '{{ $item->start_time }}'
                        )"
                            class="px-3 py-1 text-[10px] sm:text-xs rounded bg-yellow-500 text-white hover:bg-yellow-600 transition whitespace-nowrap">
                            {{ __('messages.reschedule') }}
                        </button>
                    @endif
                </div>

                <div
                    class="flex flex-row sm:flex-col items-center sm:items-end justify-end gap-3 sm:gap-2 w-full sm:w-auto mt-2 sm:mt-0">
                    <div class="order-2 sm:order-1">
                        @if ($item->has_been_reviewed)
                            <a
                                href="{{ route('patient.appointments.review.edit', $item->id) }}"class="px-4 py-1.5 bg-[#00C3B3] hover:bg-[#179990] text-white text-xs sm:text-sm rounded-md flex items-center justify-center transition-colors whitespace-nowrap">
                                {{ __('messages.editreview') }}
                            </a>
                        @else
                            <a href="{{ route('patient.appointments.review.create', $item->id) }}"
                                class="px-4 py-1.5 bg-[#00C3B3] hover:bg-[#179990] text-white text-xs sm:text-sm rounded-md flex items-center justify-center transition-colors whitespace-nowrap">
                                {{ __('messages.givereview') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    @empty
        <div class="bg-white p-6 text-center rounded-md border-grey-border border flex flex-col gap-6">
            <p class="text-gray-500">{{ __('messages.historynotfound') }}</p>
        </div>
    @endforelse
</div>
