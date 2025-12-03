
@php
$navItems = [
    ['icon' => 'assets/icons/home.svg', 'text' => 'Dashboard', 'route' => 'dashboard.index', 'active' => (Route::is('dashboard.index') || Route::is('settings.profile'))],
    ['icon' => 'assets/icons/find-user.svg', 'text' => 'Find Psychologist', 'route' => 'find.psychologist', 'active' => ( Route::is('find.psychologist') || Route::is('psychologist.profile') || Route::is('psychologist.review') )],
    ['icon' => 'assets/icons/book.svg', 'text' => 'Book Appointment', 'route' => 'book.appointment', 'active' => Route::is('book.appointment')],
    ['icon' => 'assets/icons/calendar.svg', 'text' => 'Appointments', 'route' => 'appointments.index', 'active' => Route::is('appointments.index')],
    ['icon' => 'assets/icons/messages.svg', 'text' => 'Messages', 'route' => 'messages', 'active' => Route::is('messages')],
];
@endphp

<aside class="flex w-16 sm:w-1/6 h-screen sticky top-0 p-2 sm:p-2 md:p-4 bg-white border border-grey-border transition-all duration-300">
    <div class="w-full flex flex-col gap-4 sm:gap-6 items-center sm:items-center">

        {{-- Logo --}}
        <div class="flex justify-center logo w-12 sm:w-20 md:w-28 lg:w-60">
            <a href="{{ route('dashboard.index') }}">
            {!! file_get_contents(public_path('assets/logo/logo.svg')) !!}
            </a>
        </div>

        @include('components.language-toggle')

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
