@php
$navItems = [
    ['icon' => 'assets/icons/home.svg', 'text' => 'Dashboard', 'route' => 'dashboard', 'active' => Route::is('dashboard')],
    ['icon' => 'assets/icons/find-user.svg', 'text' => 'Find Psychologist', 'route' => 'find.psychologist', 'active' => ( Route::is('find.psychologist') || Route::is('psychologist.profile') )],
    ['icon' => 'assets/icons/book.svg', 'text' => 'Book Appointment', 'route' => 'book.appointment', 'active' => Route::is('book.appointment')],
    ['icon' => 'assets/icons/calendar.svg', 'text' => 'Appointments', 'route' => 'appointments', 'active' => Route::is('appointments')],
    ['icon' => 'assets/icons/messages.svg', 'text' => 'Messages', 'route' => 'messages', 'active' => Route::is('messages')],
];  
@endphp

<aside class="flex w-16 sm:w-1/6 h-screen sticky top-0 p-6 sm:p-2 md:p-4 bg-white border border-grey-border justify-start transition-all duration-300">
    <div class="w-full flex flex-col gap-4 sm:gap-6 items-start sm:items-start">

        {{-- Logo --}}
        <div class="logo">
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
