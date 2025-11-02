@props([
    'text',
    'active' => false,
    'secondary' => false,
    'route' => '#'
])

<a href="{{ $route }}" 
   class="
        {{ $active 
            ? 'bg-primary text-white' 
            : ($secondary
            ? 'bg-white hover:bg-caption/5 text-caption-dark border border-grey-border'
            : 'bg-background text-caption-dark border border-grey-border hover:bg-grey-100')
            }}
        rounded-md 
        px-4
        py-2
        text-center
        flex
        flex-1
        items-center
        justify-center
        text-sm
        lg:text-base
    ">
    {{ $text }}
</a>