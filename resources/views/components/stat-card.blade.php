@props([
    'title' => '',
    'value' => '',
    'subtitle' => '',
    'icon' => '',
    'iconBackground' => '#DBEAFE',
    'iconColor' => '#2563EB',
    'trend' => 0, 
    'trendIcon' => 'assets/icons/arrow-up.svg',
    'className' => '',
])

<div {{ $attributes->merge([
    'class' => "flex flex-1 flex-col bg-white p-4 md:p-6 gap-4 sm:gap-6 rounded-md border-grey-border border {$className}"]) }}>
    <div class="flex gap-2 justify-between items-start">
        <div class="p-2 sm:p-[10px] md:p-[14px] lg:p-2 rounded-md" style="background-color: {{ $iconBackground }}">
            @if($icon)
                {!! str_replace(
                    '<svg ',
                    '<svg class="w-5 h-5 sm:w-3 sm:h-3 md:w-[#14px] md:h-[#14px] lg:w-5 lg:h-5 items-center justify-center flex" style="color: ' . $iconColor . '" fill="currentColor" ',
                    file_get_contents(public_path($icon))
                ) !!}
            @else
                {{ $slot }}
            @endif
        </div>

        @if($trend != 0)
            <div class="flex flex-row gap-1 items-center">
                <img src="{{ asset($trendIcon) }}" class="w-3 h-3 sm:w-3 sm:h-3" alt="trend">
                <p class="text-xs sm:text-sm text-[#16A34A]">+{{ $trend }}%</p>
            </div>
        @elseif ($trend == 0)
            <div class="flex flex-row gap-1 items-center">
                <div class="w-3 h-[2px] mt-[3px] bg-caption rounded-sm" alt="trend"></div>
                <p class="text-xs sm:text-sm text-caption">{{ $trend }}%</p>
            </div>
        @else
            <div class="flex flex-row gap-1 items-center">
                <img src="{{ asset($trendIcon) }}" class="w-3 h-3 sm:w-3 sm:h-3 rotate-180" style="filter: invert(100%) sepia(51%) saturate(2878%) hue-rotate(450deg);" alt="trend">
                <p class="text-xs sm:text-sm text-[#FF383C]">-{{ $trend }}%</p>
            </div>
        @endif
    </div>
    
    <div class="flex flex-col">
        <p class="text-xs sm:text-sm text-caption text-caption">{{ $title }}</p>
        <h1 class='font-bold text-xl sm:text-2xl mt-1'>{{ $value }}</h1>
        @if($subtitle)
            <p class="text-xs sm:text-sm text-caption text-caption mt-1">{{ $subtitle }}</p>
        @endif
    </div>
</div>