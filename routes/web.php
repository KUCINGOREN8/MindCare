<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PsychologistController;
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
    Route::get('/dashboard' , [DashboardController::class, 'showDashboard'])->name('dashboard');

    Route::get('find-psychologist', [PsychologistController::class, 'showFindPsychologist']) -> name('find.psychologist');

    Route::get('psychologist/{id}', [PsychologistController::class, 'showProfile']) -> name('psychologist.profile');
    
    Route::get('book_appointment', function () {
        return view('pages.appointment.book');
    }) -> name('book.appointment');

    Route::get('appointments', function () {
        return view('pages.appointment.history');
    }) -> name('appointments');


    Route::get('messages', function () {
        return view('pages.message.index');
    }) -> name('messages');

    // Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});


Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});


// Route::get('/user/psychologist/dashboard', function () {
//     return view('pages.dashboard.index');
// });