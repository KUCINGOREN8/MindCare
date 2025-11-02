@props([
    'navItems' => [],
])

<aside class="flex w-16 sm:w-1/6 h-screen p-6 sm:p-2 md:p-4 bg-background border border-grey-border justify-start transition-all duration-300">
    <div class="w-full flex flex-col gap-4 sm:gap-6 items-start sm:items-start">

        {{-- Logo --}}
        <div class="logo">
            {!! file_get_contents(public_path('image/logo/logo.svg')) !!}
        </div>

        {{-- Nav Items --}}
        <div class="flex flex-col nav-items gap-6">
            @foreach($navItems as $item)
                <x-nav-item 
                    icon="{{ $item['icon'] }}" 
                    text="{{ $item['text'] }}" 
                    :route="$item['route'] ?? '#'"
                    :active="$item['active'] ?? false"
                />
            @endforeach
        </div>

    </div>
</aside>
