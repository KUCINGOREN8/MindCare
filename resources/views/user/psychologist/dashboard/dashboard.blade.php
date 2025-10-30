@extends('layouts.user.dashboard')

@php
$navItems = [
    ['icon' => 'image/icons/home.svg', 'text' => 'Dashboard', 'route' => '/psychologist/dashboard', 'active' => true],
    ['icon' => 'image/icons/appointments.svg', 'text' => 'Appointments', 'route' => '/psychologist/appointments'],
    ['icon' => 'image/icons/users.svg', 'text' => 'My Clients', 'route' => '/psychologist/settings'],
    ['icon' => 'image/icons/messages.svg', 'text' => 'Messages', 'route' => '/psychologist/messages'],
];
@endphp

@section('content')
    <h1 class="text-2xl font-bold">Welcome, Psychologist!</h1>
@endsection
