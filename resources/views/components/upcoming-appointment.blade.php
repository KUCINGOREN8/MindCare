<div class="bg-white p-6 flex flex-col gap-6 rounded-md border-grey-border border">
    <div class="flex flex-1 gap-4 justify-between items-start">
        <h3 class="font-bold">Upcoming Appointments</h3>
        <a href="{{ route('patient.appointments.index') }}" class="underline hover:text-primary text-caption text-sm">See all</a>
    </div>

    @if($upcomingAppointments->count() > 0)
        @foreach ($upcomingAppointments as $appointment)
            <x-appointment-card
                :appointment="$appointment"
            />
        @endforeach
    @else
        <div class="bg-white p-6 text-center rounded-md border-grey-border border flex flex-col gap-6">
            <p class="text-gray-500">No upcoming appointment found</p>

            @if ($user->role === 'patient')
                <div>
                    <a href="{{ route('patient.book.appointment') }}" class="px-4 py-2 bg-[#00C3B3] hover:bg-[#179990] text-white rounded-md items-center justify-center">
                        Book your first session
                    </a>
                </div>
            {{-- Psikolog belom --}}
            @endif
        </div>
    @endif
</div>
