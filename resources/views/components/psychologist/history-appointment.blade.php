@props([
    'appointments' => [],
])

<div class="bg-white p-6 flex flex-col gap-6 rounded-md border-grey-border border">
    <div class="flex flex-1 items-start">
        <h3 class="font-bold">{{ __('psychologist_dashboard.history_title') }}</h3>
    </div>

    @forelse($appointments as $item)
        @php
            $statusColors = [
                'completed' => [
                    'icon' => 'bg-green-50 text-green-500',
                    'badge' => 'bg-green-100 text-green-700',
                ],
                'confirmed' => [
                    'icon' => 'bg-blue-50 text-blue-500',
                    'badge' => 'bg-blue-100 text-blue-700',
                ],
                'pending_payment' => [
                    'icon' => 'bg-yellow-50 text-yellow-500',
                    'badge' => 'bg-yellow-100 text-yellow-700',
                ],
                'cancelled' => [
                    'icon' => 'bg-red-50 text-red-500',
                    'badge' => 'bg-red-100 text-red-700',
                ],
                'pending' => [
                    'icon' => 'bg-gray-50 text-gray-500',
                    'badge' => 'bg-gray-100 text-gray-700',
                ],
            ];

            $statusConfig = $statusColors[$item->status] ?? $statusColors['pending'];

            // TRANSLATE STATUS LOGIC
            if ($item->status === 'pending_payment') {
                $statusText = __('psychologist_dashboard.status_awaiting_payment');
            } else {
                // Pastikan key status di file lang sama dengan value di DB (e.g., status_completed)
                $statusText = __('psychologist_dashboard.status_' . $item->status);
            }
        @endphp

        <div class="bg-white p-4 rounded-xl border border-grey-border hover:shadow-md transition flex items-center justify-between group cursor-pointer"
            onclick="window.location.href='{{ route('psychologist.clients.details', $item->user_id) }}'">

            <div class="flex items-center gap-4">
                <div class="relative">
                    <div
                        class="w-10 h-10 rounded-full {{ $statusConfig['icon'] }} flex items-center justify-center group-hover:bg-green-500 group-hover:text-white transition">
                        {{ substr($item->user->full_name ?? 'P', 0, 1) }}
                    </div>
                </div>

                <div>
                    <p class="font-bold text-gray-800 text-sm">
                        {{ $item->user->full_name ?? __('psychologist_dashboard.unknown_patient') }}
                    </p>
                    <p class="text-xs text-gray-500">
                        {{-- TRANSLATE DATE --}}
                        {{ \Carbon\Carbon::parse($item->date)->translatedFormat('d F Y') }} •
                        {{ $item->start_time }} - {{ $item->end_time }}

                        @if ($item->user->gender || $item->user->date_of_birth)
                            <br>
                            <span class="text-gray-400">
                                @if ($item->user->gender)
                                    {{ $item->user->gender == 'female' ? __('psychologist_dashboard.female') : __('psychologist_dashboard.male') }}
                                @endif
                                @if ($item->user->date_of_birth)
                                    • {{ \Carbon\Carbon::parse($item->user->date_of_birth)->age }}
                                    {{ __('psychologist_dashboard.years_old') }}
                                @endif
                            </span>
                        @endif
                    </p>

                    @if ($item->notes)
                        <p class="text-xs text-gray-400 mt-1 italic max-w-md truncate">
                            "{{ Str::limit($item->notes, 60) }}"
                        </p>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-3">
                <span
                    class="px-3 py-1 rounded-full text-[10px] font-bold {{ $statusConfig['badge'] }} uppercase tracking-wide">
                    {{ $statusText }}
                </span>

                <div class="text-gray-300 group-hover:text-primary transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white p-6 text-center rounded-md border-grey-border border flex flex-col gap-6">
            <p class="text-gray-500">{{ __('psychologist_dashboard.empty_history') }}</p>
            <p class="text-sm text-gray-400">{{ __('psychologist_dashboard.empty_history_desc') }}</p>
        </div>
    @endforelse
</div>
