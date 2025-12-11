@props([
    'text',
    'active' => true,
    'secondary' => false,
    'route' => '#'
])

@php
    if (!$active && !$secondary) {
        $classes = 'bg-gray-100 text-gray-400 cursor-not-allowed border border-gray-200';
    } elseif ($active && !$secondary) {
        $classes = 'bg-primary text-white hover:bg-[#179990]';
    } elseif ($secondary) {
        $classes = 'bg-white hover:bg-gray-100 text-caption-dark border border-grey-border';
    } else {
        $classes = 'bg-background text-caption-dark border border-grey-border hover:bg-gray-100';
    }
    $classes .= ' rounded-md px-2 md:px-4 py-2 md:py-2 text-center flex flex-1 items-center justify-center text-xs sm:text-sm lg:text-base';
@endphp

@if(!$active && !$secondary)
    <span class="{{ $classes }}">
        {{ $text }}
    </span>
@else
    <a href="{{ $route }}" class="{{ $classes }}">
        {{ $text }}
    </a>
@endif
