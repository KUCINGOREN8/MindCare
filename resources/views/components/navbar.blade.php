@props([
    'role' => 'patient',
])

@php
use App\Helpers\NavigationHelper;

$navItems = NavigationHelper::getNavItems($role);
$currentRoute = request()->route()->getName();
$logoRoute = match($role) {
    'patient' => 'patient.dashboard',
    'psychologist' => 'psychologist.dashboard',
    // 'admin' => 'admin.dashboard',
    default => 'login'
};
@endphp


<aside class="flex w-16 sm:w-1/6 h-screen sticky top-0 p-2 sm:p-2 md:p-4 bg-white border border-grey-border transition-all duration-300">
    <div class="w-full flex flex-col gap-4 sm:gap-6 items-center sm:items-center">

        {{-- Logo --}}
        <div class="flex justify-center logo w-12 sm:w-20 md:w-28 lg:w-60">
            <a href="{{ route($logoRoute ) }}">
                {!! file_get_contents(public_path('assets/logo/logo.svg')) !!}
            </a>
        </div>

        @include('components.language-toggle')

        {{-- Nav Items --}}
        <div class="flex flex-col w-full  nav-items gap-6">
            @foreach($navItems as $item)
                <x-nav-item
                    icon="{{ $item['icon'] }}"
                    text="{{ $item['text'] }}"
                    :route="$item['route'] ?? '#'"
                    :active="NavigationHelper::isActive($item['patterns'] ?? [])"
                />
            @endforeach
        </div>

    </div>
</aside>
