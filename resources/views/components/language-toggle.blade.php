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