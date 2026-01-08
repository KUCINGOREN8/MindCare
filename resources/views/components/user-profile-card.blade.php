@props([
    'user' => null,
    'notifications' => [],
    'showSettings' => true,
    'showLogout' => true,
    'showNotifications' => true,
    'showStatus' => true,
    'maxWidth' => '100%',
    'showTitle' => true,
])

@if ($user)
    <div {{ $attributes->merge(['class' => 'flex flex-col p-4 sm:p-6 gap-6 bg-white rounded-md border-grey-border border w-full h-fit']) }}>
        <div class="flex flex-col gap-4 justify-start">
            {{-- User Information --}}
            <div class="flex flex-row gap-4 transition-all duration-300">
                <img src="{{ $user->photo_url }}"
                    class="rounded-full w-12 h-12 sm:w-16 sm:h-16 object-cover flex-shrink-0"
                    alt="{{ $user->full_name }} profile picture">
                <div class="flex flex-col justify-center text-left min-w-0">
                    <h4 class="user-name font-semibold text-sm sm:text-base truncate">{{ $user->full_name }}</h4>

                    @if ($showTitle && $user->psychologist && $user->psychologist->title && $user->role === 'psychologist')
                        <p class="text-caption text-xs sm:text-sm text-gray-500 truncate">
                            {{ $user->psychologist->title }}</p>
                    @elseif($showTitle && $user->role === 'patient')
                        <p class="text-caption text-xs sm:text-sm text-gray-500">Patient</p>
                    @elseif($showTitle && $user->role === 'admin')
                        <p class="text-caption text-xs sm:text-sm text-gray-500">Administrator</p>
                    @endif

                    @if ($showStatus)
                        <div class="flex gap-2 items-center mt-1">
                            <div class="rounded-full w-2 h-2 bg-primary"></div>
                            <p class="text-primary text-xs sm:text-sm">{{ __('messages.active') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Action Buttons --}}
            @if ($showSettings || $showLogout)
                <div class="flex gap-3 flex-row w-full">
                    @if ($showSettings)
                        @php
                            $settingsRoute = $customSettingsRoute ?? route('profile.index');
                        @endphp
                        <x-rounded-button text="{{ __('messages.setting') }}" active="true"
                            class="w-full justify-center text-xs sm:text-sm"
                            route="{{ $settingsRoute }}"></x-rounded-button>
                    @endif

                    @if ($showLogout)
                        <form action="{{ route('logout') }}" method="POST" class="flex flex-1">
                            @csrf
                            <button type="submit"
                                class="rounded-button secondary bg-white hover:bg-gray-100 text-caption-dark border border-grey-border rounded-md px-2 py-2 w-full text-center flex items-center justify-center text-xs sm:text-sm h-full whitespace-nowrap">
                                {{ __('messages.logout') }}
                            </button>
                        </form>
                    @endif
                </div>
            @endif
        </div>

        {{-- Notifications --}}
        @if ($showNotifications)
            <div class="border-t border-gray-100 pt-4">
                @include('components.notifications', ['notifications' => $notifications])
            </div>
        @endif
    </div>
@else
    <div class="text-center p-6 text-gray-500">
        {{ __('messages.nouserdata') }}
    </div>
@endif
