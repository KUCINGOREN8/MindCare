<div class="sticky top-0 z-50">
    <nav class="bg-white shadow-md" x-data="{ isOpen: false }">
        <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
            <div class="relative flex h-16 items-center justify-between">

                <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
                    <button type="button" @click="isOpen = !isOpen"
                        class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-[#00C3B3]">
                        <span class="absolute -inset-0.5"></span>
                        <span class="sr-only">Open main menu</span>

                        <svg :class="{ 'hidden': isOpen, 'block': !isOpen }" class="block size-6" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>

                        <svg :class="{ 'block': isOpen, 'hidden': !isOpen }" class="hidden size-6" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start">
                    <div class="flex shrink-0 items-center">
                        <img src="{{ asset('img/logo.svg') }}" alt="Your Company" class="h-8 w-auto" />
                    </div>

                    <div class="hidden sm:ml-6 sm:block">
                        <div class="flex space-x-4 font-Inter">
                            <a href="#home"
                                class="rounded-full px-4 py-2 bg-[#00C3B3] text-sm font-semibold text-white hover:bg-[#00A89B] transition">Home</a>
                            <a href="#about"
                                class="rounded-full px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-black transition">{{ __('messages.about') }}</a>
                            <a href="#testimonials"
                                class="rounded-full px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-black transition">{{ __('messages.testimonials') }}</a>
                        </div>
                    </div>
                </div>

                <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">

                    <div class="mr-4">
                        @include('components.language-toggle')
                    </div>

                    <div class="hidden sm:flex items-center gap-2">
                        @auth
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit"
                                    class="bg-red-500 hover:bg-red-600 active:bg-red-700 text-white font-semibold px-4 py-2 rounded-full transition text-sm">
                                    {{ __('messages.logout') }}
                                </button>
                            </form>
                        @else
                            <a href="{{ route('signup') }}"
                                class="bg-[#00C3B3] hover:bg-[#33D1C2] active:bg-[#66DED0] text-white font-semibold px-4 py-2 rounded-full transition text-sm">
                                {{ __('messages.signup') }}
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <div x-show="isOpen" x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95" class="sm:hidden" id="mobile-menu">

            <div class="space-y-1 px-2 pt-2 pb-3">
                <a href="#home" class="block rounded-md bg-[#00C3B3] px-3 py-2 text-base font-medium text-white"
                    aria-current="page">Home</a>
                <a href="#about"
                    class="block rounded-md px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-100 hover:text-black">{{ __('messages.about') }}</a>
                <a href="#testimonials"
                    class="block rounded-md px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-100 hover:text-black">{{ __('messages.testimonials') }}</a>

                <div class="border-t border-gray-200 pt-4 mt-2">
                    @auth
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="block w-full text-left rounded-md px-3 py-2 text-base font-medium text-red-600 hover:bg-red-50">
                                {{ __('messages.logout') }}
                            </button>
                        </form>
                    @else
                        <a href="{{ route('signup') }}"
                            class="block rounded-md px-3 py-2 text-base font-medium text-[#00C3B3] hover:bg-gray-50">
                            {{ __('messages.signup') }}
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
</div>
