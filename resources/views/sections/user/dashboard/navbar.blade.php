@props([
    'navItems' => [],      // array of nav items: ['icon'=>..., 'text'=>..., 'route'=>..., 'active'=>bool]
])

<aside class="flex w-1/6 h-screen p-6 bg-background shadow-md justify-start">
    <div class="w-full flex flex-col gap-6">

        {{-- Logo --}}
        <div class="logo">
            <img src="{{ asset('image/logo/logo.svg') }}" alt="Logo">
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
