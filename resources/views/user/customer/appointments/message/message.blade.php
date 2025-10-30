@extends('layouts.user.dashboard')

@php
$navItems = [
    ['icon' => 'image/icons/home.svg', 'text' => 'Dashboard', 'route' => '/user/customer/dashboard',],
    ['icon' => 'image/icons/find-user.svg', 'text' => 'Find Psychologist', 'route' => '/user/customer/find-psychologist'],
    ['icon' => 'image/icons/book.svg', 'text' => 'Book Appointment', 'route' => '/user/customer/book_appointment'],
    ['icon' => 'image/icons/calendar.svg', 'text' => 'Appointments', 'route' => '/user/customer/appointments'],
    ['icon' => 'image/icons/messages.svg', 'text' => 'Messages', 'route' => '/user/customer/messages', 'active' => true],
];
@endphp

@section('content')
    <h1 class="text-2xl font-bold">Check Your Messages</h1>
@endsection