<div class="sticky top-0 z-50">
    <nav class="bg-white shadow-md">
        <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
            <div class="relative flex h-16 items-center justify-between">
                <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
                </div>
                <div class="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start">
                    <div class="flex shrink-0 items-center">
                        <a href="{{ route('dashboard') }}">
                        <img src="img/logo.svg" alt="Your Company" class="h-8 w-auto" />
                        </a>
                    </div>
                </div>
                <div class="mr-4 items-center justify-center">
                    @php
                        $isEnglish = session('locale', 'en') === 'en';
                    @endphp

                    <div class="flex items-center gap-2 px-3 py-1 bg-gray-50 rounded-full shadow-sm">
                        <a href="{{ route('switch.lang', ['lang' => 'en']) }}"
                            class="{{ $isEnglish ? 'font-semibold text-gray-800' : 'text-gray-400' }} hover:text-teal-500 transition">
                            EN
                        </a>

                        <span class="text-gray-400">|</span>

                        <a href="{{ route('switch.lang', ['lang' => 'id']) }}"
                            class="{{ !$isEnglish ? 'font-semibold text-gray-800' : 'text-gray-400' }} hover:text-teal-500 transition">
                            ID
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</div>
