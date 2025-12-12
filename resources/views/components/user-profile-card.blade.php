{{-- resources/views/components/user-profile-card.blade.php --}}
@props([
    'user' => null,
    'notifications' => [],
    'showSettings' => true,
    'showLogout' => true,
    'showNotifications' => true,
    'showStatus' => true,
    'maxWidth' => '300px',
    'showTitle' => true
])

@if($user)
    <div {{ $attributes->merge(['class' => 'flex flex-col p-6 gap-6 bg-white rounded-md border-grey-border border']) }} style="max-width: {{ $maxWidth }}">
        <div class="flex flex-col gap-4 justify-start">
            {{-- User Information --}}
            <div class="flex flex-col gap-4 lg:flex-row transition-all duration-300">
                <img
                    src="{{ $user->photo_url ? asset($user->photo_url) : ($user->gender == 'female' ? asset('assets/icons/user_female.svg') : asset('assets/icons/user_male.svg')) }}"
                    class="rounded-full w-16 h-16 lg:mx-0 mx-auto"
                    alt="{{ $user->full_name }} profile picture"
                >
                <div class="flex flex-col justify-left text-left">
                    <h4 class="user-name font-semibold">{{ $user->full_name }}</h4>

                    @if($showTitle && $user->psychologist && $user->psychologist->title && $user->role === 'psychologist')
                        <p class="text-caption">{{ $user->psychologist->title }}</p>
                    @elseif($showTitle && $user->role === 'patient')
                        <p class="text-caption">Patient</p>
                        @elseif($showTitle && $user->role === 'admin')
                        <p class="text-caption">Administrator</p>
                    @endif

                    @if($showStatus)
                        <div class="flex gap-2 items-center">
                            <div class="rounded-full w-2 h-2 bg-primary"></div>
                            <p class="text-primary text-sm">Active</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Action Buttons --}}
            @if($showSettings || $showLogout)
                <div class="flex gap-4 flex-col lg:flex-row">
                    @if($showSettings)
                        @php
                            $settingsRoute = $customSettingsRoute ?? route('profile.index');
                        @endphp
                        <x-rounded-button
                            text="Settings"
                            active="true"
                            route="{{ $settingsRoute }}"
                        ></x-rounded-button>
                    @endif

                    @if($showLogout)
                        <form action="{{ route('logout') }}" method="POST" class="flex flex-1">
                            @csrf
                            <button type="submit" class="rounded-button secondary bg-white hover:bg-gray-100 text-caption-dark border border-grey-border rounded-md px-2 md:px-4 py-2 md:py-2 text-center flex flex-1 items-center justify-center text-xs sm:text-sm lg:text-base">
                                Logout
                            </button>
                        </form>
                    @endif
                </div>
            @endif
        </div>

        {{-- Notifications --}}
        @if($showNotifications)
            @include('components.notifications', ['notifications' => $notifications])
        @endif
    </div>
@else
    <div class="text-center p-6 text-gray-500">
        User information not available
    </div>
@endif
