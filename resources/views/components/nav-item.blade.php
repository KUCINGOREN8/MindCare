@props([
    'icon',
    'text',
    'active' => false,
    'route' => '#'
])

<a href="{{ $route }}" class="flex items-center w-full h-12 gap-3 p-4 rounded-md cursor-pointer
    {{ $active ? 'bg-primary/10' : 'bg-transparent hover:bg-primary/5' }}">

    {{-- Inline SVG --}}
    {!! str_replace(
        '<svg ',
        '<svg class="'.($active ? 'text-primary' : 'text-[#A1AAB2]').'" fill="currentColor" ',
        file_get_contents(public_path($icon))
    ) !!}

    {{-- Text --}}
    <span class="{{ $active ? 'text-primary font-semibold' : 'text-[#A1AAB2]' }}">
        {{ $text }}
    </span>
</a>
