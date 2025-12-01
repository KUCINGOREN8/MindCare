
@php
$navItems = [
    ['icon' => 'assets/icons/home.svg', 'text' => 'Dashboard', 'route' => 'dashboard.index', 'active' => Route::is('dashboard.index')],
    ['icon' => 'assets/icons/find-user.svg', 'text' => 'Find Psychologist', 'route' => 'find.psychologist', 'active' => ( Route::is('find.psychologist') || Route::is('psychologist.profile') )],
    ['icon' => 'assets/icons/book.svg', 'text' => 'Book Appointment', 'route' => 'book.appointment', 'active' => Route::is('book.appointment')],
    ['icon' => 'assets/icons/calendar.svg', 'text' => 'Appointments', 'route' => 'appointments.index', 'active' => Route::is('appointments.index')],
    ['icon' => 'assets/icons/messages.svg', 'text' => 'Messages', 'route' => 'messages', 'active' => Route::is('messages')],
];  
@endphp

<aside class="flex w-16 sm:w-1/6 h-screen sticky top-0 p-3 sm:p-1 md:p-4 bg-white border border-grey-border transition-all duration-300">
    <div class="w-full flex flex-col gap-4 sm:gap-6 items-center sm:items-center">

        {{-- Logo --}}
        <div class="logo w-12 sm:w-20">
            {!! file_get_contents(public_path('assets/logo/logo.svg')) !!}
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
