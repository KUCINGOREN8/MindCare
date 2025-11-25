<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup');
Route::post('/signup', [AuthController::class, 'register'])->name('register');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});


Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
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