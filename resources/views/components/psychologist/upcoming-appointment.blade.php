@props([
    'upcomingAppointments' => [],
    'showSeeAll' => true,
])

<div class="bg-white p-6 flex flex-col gap-6 rounded-md border-grey-border border">
    <div class="flex flex-1 gap-4 justify-between items-start">
        <div class="flex items-center gap-2">
            {{-- Indikator Ping (Hanya visual, tidak perlu translate) --}}
            @if ($upcomingAppointments->count() > 0)
                <span class="relative flex h-3 w-3 ml-1">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-teal-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-teal-500"></span>
                </span>
            @endif

            {{-- TITLE --}}
            <h3 class="font-bold">{{ __('psychologist_dashboard.upcoming_sessions_title') }}</h3>
        </div>

        {{-- SEE ALL LINK --}}
        @if ($showSeeAll && $upcomingAppointments->count() > 2)
            <a href="{{ route('psychologist.appointments.index') }}"
                class="underline hover:text-primary text-caption text-sm">
                {{ __('psychologist_dashboard.see_all') }}
            </a>
        @endif
    </div>

    @if ($upcomingAppointments->count() > 0)
        @foreach ($showSeeAll ? $upcomingAppointments->take(2) : $upcomingAppointments as $appointment)
            <x-psychologist.appointment-card :appointment="$appointment" />
        @endforeach
    @else
        {{-- EMPTY STATE --}}
        <div class="bg-white p-6 text-center rounded-md border-grey-border border flex flex-col gap-6">
            <p class="text-gray-500">{{ __('psychologist_dashboard.no_upcoming_sessions') }}</p>

            @if (auth()->user()->role === 'psychologist')
                <div>
                    <a href="{{ route('psychologist.clients') }}"
                        class="px-4 py-2 bg-[#00C3B3] hover:bg-[#179990] text-white rounded-md items-center justify-center">
                        {{ __('psychologist_dashboard.btn_view_clients') }}
                    </a>
                </div>
            @endif
        </div>
    @endif
</div>
