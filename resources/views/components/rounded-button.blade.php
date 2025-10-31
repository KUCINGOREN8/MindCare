@props([
    'text',
    'active' => false,
    'route' => '#'
])

<a href="{{ $route }}" 
   class="
        {{ $active 
            ? 'bg-primary text-white' 
            : 'bg-background hover:bg-caption/5 text-caption-dark' }}
        rounded-md 
        px-4
        py-2
        inline-block
        text-center
    ">
    {{ $text }}
</a>