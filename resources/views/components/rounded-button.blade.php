@props([
    'text',
    'active' => false,
    'secondary' => false,
    'route' => '#'
])

<a href="{{ $route }}" 
   class="
        {{ $active 
            ? 'bg-primary text-white hover:bg-[#179990]' 
            : ($secondary
            ? 'bg-white hover:bg-gray-100 text-caption-dark border border-grey-border'
            : 'bg-background text-caption-dark border border-grey-border hover:bg-gray-100')
            }}
        rounded-md 
        px-2
        md:px-4
        py-2
        md:py-2
        text-center
        flex
        flex-1
        items-center
        justify-center
        text-xs
        sm:text-sm
        lg:text-base
    ">
    {{ $text }}
</a>