{{-- resources/views/components/recent-clients.blade.php --}}
@props([
    'user' => null,
    'limit' => 2,
    'showAllLink' => true,
])

<div class="bg-white p-6 flex flex-1 flex-col gap-6 rounded-md border-grey-border border h-fit">
    <div class="flex gap-4 justify-between items-start">
        <h3 class="font-bold">{{ __('psychologist_dashboard.recent_clients_title') }}</h3>
        @if ($showAllLink)
            <a href="{{ route('psychologist.clients') }}" class="underline hover:text-primary text-caption text-sm">
                {{ __('psychologist_dashboard.see_all') }}
            </a>
        @endif
    </div>

    <div class="flex flex-col gap-3">
        @php
            $appointments = $user->psychologist->appointments()->with('user')->orderBy('created_at', 'desc')->get();

            $uniqueAppointments = $appointments->unique('user_id')->take($limit);
        @endphp

        @if ($uniqueAppointments->count() > 0)
            @foreach ($uniqueAppointments as $appointment)
                @php
                    $patient = $appointment->user;
                @endphp

                @if ($patient)
                    <div class="bg-white p-3 flex flex-1 gap-6 rounded-md border-grey-border border hover:bg-gray-50 transition-colors cursor-pointer"
                        onclick="window.location.href='{{ route('psychologist.clients.details', $patient->id) }}'">
                        <div class="flex flex-1 justify-between items-center">
                            <div class="flex flex-row gap-3 items-center">
                                <img src="{{ $patient->photo_url ? asset($patient->photo_url) : ($patient->gender == 'female' ? asset('assets/icons/user_female.svg') : asset('assets/icons/user_male.svg')) }}"
                                    class="rounded-full w-12 h-12 lg:mx-0 mx-auto object-cover" alt="pfp">
                                <div>
                                    <p class="font-medium">{{ $patient->full_name }}</p>
                                    @if ($patient->email)
                                        <p class="text-xs text-gray-500">{{ $patient->email }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right">
                                @if ($appointment->date)
                                    <p class="text-sm text-gray-600">
                                        {{ __('psychologist_dashboard.last_session_label') }}
                                        {{ \Carbon\Carbon::parse($appointment->date)->translatedFormat('d M Y') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        @else
            <div class="bg-white p-6 text-center rounded-md border-grey-border border">
                <p class="text-gray-500">{{ __('psychologist_dashboard.no_recent_clients') }}</p>
                <p class="text-sm text-gray-400 mt-1">{{ __('psychologist_dashboard.no_clients_desc') }}</p>
            </div>
        @endif
    </div>
</div>
