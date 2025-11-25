<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


// Route::get('/{userid}/dashboard', function () {
//     return view('pages.dashboard.index');
// });

Route::get('/user/customer/dashboard', function () {
    return view('pages.dashboard.index');
})-> name('dashboard');

Route::get('/user/customer/find-psychologist', function () {
    return view('pages.psychologist.find');
}) -> name('find.psychologist');

Route::get('/user/customer/book_appointment', function () {
    return view('pages.appointment.book');
}) -> name('book.appointment');

Route::get('/user/customer/appointments', function () {
    return view('pages.appointment.history');
}) -> name('appointments');

Route::get('/user/customer/psychologist/{id}', function () {
    return view('pages.psychologist.profile');
}) -> name('psychologist.profile');

Route::get('/user/customer/messages', function () {
    return view('pages.message.index');
}) -> name('messages');

Route::get('/user/psychologist/dashboard', function () {
    return view('pages.dashboard.index');
});