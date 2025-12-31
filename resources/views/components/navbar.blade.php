@props([
    'role' => 'patient',
])

@php
    use App\Helpers\NavigationHelper;

    $navItems = NavigationHelper::getNavItems($role);
    $currentRoute = request()->route()->getName();
    $logoRoute = match ($role) {
        'patient' => 'patient.dashboard',
        'psychologist' => 'psychologist.dashboard',
        // 'admin' => 'admin.dashboard',
        default => 'login',
    };
@endphp


<aside
    class="flex w-16 sm:w-1/6 md:min-w-[222px] h-screen sticky top-0 p-2 sm:p-2 md:p-4 bg-white border border-grey-border transition-all duration-300">
    <div class="w-full md:min-w-[155px] flex flex-col gap-4 sm:gap-6 items-center sm:items-center">

        {{-- Logo --}}
        <div class="flex justify-center logo">
            <a href="{{ route($logoRoute) }}">
                {!! str_replace(
                    '<svg ',
                    '<svg class="w-[50px] sm:w-15 md:w-28 lg:w-32" ',
                    file_get_contents(public_path('assets/logo/logo.svg')),
                ) !!}
            </a>
        </div>

        <x-language-toggle :isResponsive="true" />

        {{-- Nav Items --}}
        <div class="flex flex-col w-full nav-items items-center justify-center gap-6">
            @foreach ($navItems as $item)
                <x-nav-item icon="{{ $item['icon'] }}" text="{{ $item['text'] }}" :route="$item['route'] ?? '#'" :active="NavigationHelper::isActive($item['patterns'] ?? [])" />
            @endforeach
        </div>

    </div>
</aside>
