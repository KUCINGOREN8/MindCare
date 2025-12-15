@props([
    'upcomingAppointments',
    'user',
    'showSeeAll' => true
])

<div class="bg-white p-6 flex flex-col gap-6 rounded-md border border-grey-border">

    {{-- Header --}}
    <div class="flex flex-1 gap-4 justify-between items-start">
        <div class="flex items-center gap-2">
            @if($upcomingAppointments->count() > 0)
                <span class="relative flex h-3 w-3 ml-1">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-teal-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-teal-500"></span>
                </span>
            @endif
            <h3 class="font-bold">Upcoming Appointments</h3>
        </div>

        @if($showSeeAll)
            <a
                href="{{ route('patient.appointments.index') }}"
                class="underline hover:text-primary text-caption text-sm"
            >
                See all
            </a>
        @endif
    </div>

    {{-- Appointment Cards --}}
    @if($upcomingAppointments->count() > 0)
        @foreach(($showSeeAll ? $upcomingAppointments->take(2) : $upcomingAppointments) as $appointment)
            <x-appointment-card :appointment="$appointment" />
        @endforeach
    @else
        <div class="bg-white p-6 text-center rounded-md border border-grey-border flex flex-col gap-6">
            <p class="text-gray-500">No upcoming appointment found</p>

            {{-- Button only for patient --}}
            @if ($user->role === 'patient')
                <div>
                    <a
                        href="{{ route('patient.book.appointment') }}"
                        class="px-4 py-2 bg-[#00C3B3] hover:bg-[#179990] text-white rounded-md"
                    >
                        Book your first session
                    </a>
                </div>
            @endif
        </div>
    @endif

</div>
