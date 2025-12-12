@props([
    'icon',
    'text',
    'active' => false,
    'route' => '#'
])

<a href="{{ Route($route) }}" class="flex items-center w-fit md:min-w-[158px] md:w-full gap-3 p-2 md:p-2 lg:p-3 rounded-md cursor-pointer
    {{ $active ? 'bg-primary/10' : 'bg-transparent hover:bg-primary/5' }}">

    {!! str_replace(
        '<svg ',
        '<svg class="md:items-start items-center  '.($active ? 'text-primary' : 'text-[#A1AAB2]').'" fill="currentColor" ',
        file_get_contents(public_path($icon))
    ) !!}

    <span class="
        {{ $active ? 'text-primary font-semibold' : 'text-[#A1AAB2]' }}
        hidden
        md:inline
        md:text-xs
        lg:text-sm
    ">
        {{ $text }}
    </span>
</a>
