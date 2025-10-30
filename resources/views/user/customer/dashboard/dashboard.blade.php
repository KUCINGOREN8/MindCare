@extends('layouts.user.dashboard')

@php
$navItems = [
    ['icon' => 'image/icons/home.svg', 'text' => 'Dashboard', 'route' => '/user/customer/dashboard', 'active' => true],
    ['icon' => 'image/icons/find-user.svg', 'text' => 'Find Psychologist', 'route' => '/user/customer/find-psychologist'],
    ['icon' => 'image/icons/book.svg', 'text' => 'Book Appointment', 'route' => '/book-appointment'],
    ['icon' => 'image/icons/calendar.svg', 'text' => 'Appointments', 'route' => '/appointments'],
    ['icon' => 'image/icons/messages.svg', 'text' => 'Messages', 'route' => '/messages'],
];
@endphp

@section('content')
    <h1 class="text-2xl font-bold">Welcome, Customer!</h1>
@endsection