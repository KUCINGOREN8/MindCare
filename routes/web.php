<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PsychologistController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PostController;


Route::middleware('guest')->group(function () {
    Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup');
    Route::post('/signup', [AuthController::class, 'register'])->name('register');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});


Route::middleware('auth')->group(function () {

    // --- Dashboard & Main Features ---
    Route::get('/dashboard' , [DashboardController::class, 'showDashboard'])->name('dashboard.index');
    Route::get('find-psychologist', [PsychologistController::class, 'showFindPsychologist']) -> name('find.psychologist');
    Route::get('psychologist/{id}', [PsychologistController::class, 'showProfile']) -> name('psychologist.profile');
    
    Route::get('book_appointment', function () {
        return view('pages.appointment.book');
    }) -> name('book.appointment');

    Route::get('messages', function () {
        return view('pages.message.index');
    }) -> name('messages');

    Route::prefix('appointments')
        ->name('appointments.')
        ->controller(AppointmentController::class)
        ->group(function () {
            
            // Halaman utama appointments (History/List)
            
            Route::get('/', 'index')->name('index'); 

            // Ini yang load more data ges
            Route::get('/load-more', 'loadMore')->name('load-more');

            // Actions (Confirm, Cancel, Reschedule)
            Route::post('/{id}/confirm', 'confirm')->name('confirm');
            Route::post('/{id}/cancel', 'cancel')->name('cancel');
            Route::post('/{id}/reschedule', 'reschedule')->name('reschedule');
        });


    // --- Profile Settings ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- Logout ---
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/lang/{lang}', function ($lang) {
    session(['locale' => $lang]);
    return back();
})->name('switch.lang');