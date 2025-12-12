@props([
    'isResponsive' => false, 
])

<div class="items-center justify-center">
    @php
        $isEnglish = session('locale', 'en') === 'en';
    @endphp

    @if($isResponsive)
        <div class="flex sm:flex-row flex-col items-center gap-1 sm:gap-2 
                    px-3 py-1 bg-gray-50 rounded-md shadow-sm">
            
            <a href="{{ route('switch.lang', ['lang' => 'en']) }}"
                class="{{ $isEnglish ? 'font-semibold text-gray-800' : 'text-gray-400' }} 
                       hover:text-teal-500 transition text-sm">
                EN
            </a>

            <span class="text-gray-400 text-xs sm:text-sm
                         hidden sm:inline-block">|</span>
            <div class="w-4 h-px bg-gray-300 sm:hidden"></div>

            <a href="{{ route('switch.lang', ['lang' => 'id']) }}"
                class="{{ !$isEnglish ? 'font-semibold text-gray-800' : 'text-gray-400' }} 
                       hover:text-teal-500 transition text-sm">
                ID
            </a>
        </div>
    @else
        <div class="flex items-center gap-2 px-3 py-1 bg-gray-50 rounded-full shadow-sm">
            
            <a href="{{ route('switch.lang', ['lang' => 'en']) }}"
                class="{{ $isEnglish ? 'font-semibold text-gray-800' : 'text-gray-400' }} 
                       hover:text-teal-500 transition text-sm">
                EN
            </a>

            <span class="text-gray-400">|</span>

            <a href="{{ route('switch.lang', ['lang' => 'id']) }}"
                class="{{ !$isEnglish ? 'font-semibold text-gray-800' : 'text-gray-400' }} 
                       hover:text-teal-500 transition text-sm">
                ID
            </a>
        </div>
    @endif
</div>