<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/user/customer/dashboard', function () {
    return view('user.customer.dashboard.dashboard');
});

Route::get('/user/customer/find-psychologist', function () {
    return view('user.customer.appointments.find_psychologist.find-psycologist');
});

Route::get('/user/customer/book_appointment', function () {
    return view('user.customer.appointments.book.book-appointment');
});

Route::get('/user/customer/appointments', function () {
    return view('user.customer.appointments.history.appointment-history');
});

Route::get('/user/customer/messages', function () {
    return view('user.customer.appointments.message.message');
});

Route::get('/user/psychologist/dashboard', function () {
    return view('user.psychologist.dashboard.dashboard');
});