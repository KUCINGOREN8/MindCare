@props([
    'title' => '',
    'value' => '',
    'subtitle' => '',
    'icon' => '',
    'iconBackground' => '#DBEAFE',
    'iconColor' => '#2563EB',
    'trend' => 0, 
    'trendIcon' => 'assets/icons/arrow-up.svg',
    'trendColor' => '#16A34A',
    'className' => '',
])

<div {{ $attributes->merge([
    'class' => "flex flex-1 flex-col bg-white p-4 sm:p-6 gap-4 sm:gap-6 rounded-md border-grey-border border {$className}"]) }}>
    <div class="flex gap-3 sm:gap-4 justify-between items-start">
        <div class="p-2 sm:p-[10px] rounded-md" style="background-color: {{ $iconBackground }}">
            @if($icon)
                {!! str_replace(
                    '<svg ',
                    '<svg class="w-5 h-5 sm:w-6 sm:h-6" style="color: ' . $iconColor . '" fill="currentColor" ',
                    file_get_contents(public_path($icon))
                ) !!}
            @else
                {{ $slot }}
            @endif
        </div>

        @if($trend != 0)
            <div class="flex flex-row gap-1 items-center">
                <img src="{{ asset($trendIcon) }}" class="w-3 h-3 sm:w-4 sm:h-4 mt-[3px]" alt="trend">
                <p class="text-xs sm:text-sm" style="color: {{ $trendColor }}">{{ $trend > 0 ? '+' : '' }}{{ $trend }}%</p>
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