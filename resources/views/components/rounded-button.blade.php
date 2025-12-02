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
            ? 'bg-white hover:bg-caption/2 text-caption-dark border border-grey-border'
            : 'bg-background text-caption-dark border border-grey-border hover:bg-grey-100')
            }}
        rounded-md 
        px-4
        py-2
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
<<<<<<< HEAD
        text-sm
=======
        text-xs
        sm:text-sm
>>>>>>> 05965638d654be5556a9a63ac9d22ecc8010904b
        lg:text-base
    ">
    {{ $text }}
</a>