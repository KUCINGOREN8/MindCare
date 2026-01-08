@props(['upcomingAppointments', 'user', 'showSeeAll' => true])

<div class="bg-white p-4 sm:p-6 flex flex-col gap-4 sm:gap-6 rounded-md border border-grey-border overflow-x-hidden">

    {{-- Header --}}
    <div class="flex flex-wrap gap-3 justify-between items-start min-w-0">
        <div class="flex items-center gap-2">
            @if ($upcomingAppointments->count() > 0)
                <span class="relative flex h-3 w-3 ml-1">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-teal-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-teal-500"></span>
                </span>
            @endif
            <h3 class="font-bold text-base sm:text-lg">{{ __('messages.upcomingappt') }}</h3>
        </div>

        @if ($showSeeAll)
            <a href="{{ route('patient.appointments.index') }}"
                class="underline hover:text-primary text-caption text-xs sm:text-sm whitespace-nowrap shrink-0">
                {{ __('messages.seeall') }}
            </a>
        @endif
    </div>

    {{-- Appointment Cards --}}
    @if ($upcomingAppointments->count() > 0)
        <div class="flex flex-col gap-4 w-full min-w-0 overflow-hidden">
            @foreach ($showSeeAll ? $upcomingAppointments->take(2) : $upcomingAppointments as $appointment)
                <x-appointment-card :appointment="$appointment" />
            @endforeach
        </div>
    @else
        <div
            class="bg-white p-6 text-center rounded-md border border-grey-border flex flex-col gap-4 sm:gap-6 items-center justify-center w-full min-w-0">
            <p class="text-gray-500 text-sm sm:text-base">{{ __('messages.upcomingnotfound') }}</p>

            {{-- Button only for patient --}}
            @if ($user->role === 'patient')
                <div class="w-full sm:w-auto flex justify-center">
                    <a href="{{ route('patient.book.appointment') }}"
                        class="block w-full sm:w-auto px-4 py-2 bg-[#00C3B3] hover:bg-[#179990] text-white rounded-md font-medium transition-colors text-center text-sm sm:text-base">
                        {{ __('messages.bookappointment') }}
                    </a>
                </div>
            @endif
        </div>
    @endif

</div>
