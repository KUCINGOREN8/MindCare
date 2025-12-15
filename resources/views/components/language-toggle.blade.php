@props([
    'isResponsive' => false,
])

<div class="flex items-center justify-center">
    @php
        // OPTIMASI: Ambil locale langsung dari App, bukan session
        // Ini lebih akurat karena Middleware sudah mengaturnya.
        $currentLocale = app()->getLocale();
        $isEnglish = $currentLocale === 'en';
    @endphp

    @if ($isResponsive)
        {{-- Tampilan Mobile / Responsive (Bisa Vertical/Horizontal tergantung styling parent) --}}
        <div
            class="flex sm:flex-row flex-col items-center gap-1 sm:gap-2 px-3 py-1 bg-gray-50 rounded-md shadow-sm border border-gray-100">

            <a href="{{ route('switch.lang', 'en') }}"
                class="{{ $isEnglish ? 'font-bold text-teal-600' : 'text-gray-400 hover:text-teal-500' }} transition-colors text-sm">
                EN
            </a>

            {{-- Separator: Hidden di HP, Muncul di Desktop --}}
            <span class="text-gray-300 text-xs sm:text-sm hidden sm:inline-block">|</span>

            {{-- Separator: Garis horizontal kecil di HP --}}
            <div class="w-4 h-px bg-gray-200 sm:hidden"></div>

            <a href="{{ route('switch.lang', 'id') }}"
                class="{{ !$isEnglish ? 'font-bold text-teal-600' : 'text-gray-400 hover:text-teal-500' }} transition-colors text-sm">
                ID
            </a>
        </div>
    @else
        {{-- Tampilan Desktop Biasa (Horizontal) --}}
        <div class="flex items-center gap-2 px-3 py-1 bg-white border border-gray-200 rounded-full shadow-sm">

            <a href="{{ route('switch.lang', 'en') }}"
                class="{{ $isEnglish ? 'font-bold text-teal-600' : 'text-gray-400 hover:text-teal-500' }} transition-colors text-xs font-medium">
                EN
            </a>

            <span class="text-gray-300">|</span>

            <a href="{{ route('switch.lang', 'id') }}"
                class="{{ !$isEnglish ? 'font-bold text-teal-600' : 'text-gray-400 hover:text-teal-500' }} transition-colors text-xs font-medium">
                ID
            </a>
        </div>
    @endif
</div>
