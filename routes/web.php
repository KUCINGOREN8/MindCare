<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PostController;

// Public guest-only routes (user belum login)
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

// After Login (auth protected)
Route::middleware('auth')->group(function () {
    // Dashboard pake Controller biar bisa ambil testimonials
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Logout boleh pakai POST untuk security
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Redirect root "/" → dashboard/login sesuai status login
Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Language switcher (biar bisa ganti bahasa bebas)
Route::get('/lang/{lang}', function ($lang) {
    session(['locale' => $lang]);
    return back();
})->name('switch.lang');



// appointments route group
Route::prefix('appointments')
    ->name('appointments.')
    ->controller(AppointmentController::class)
    ->group(function () {

        // halaman appointments → /appointments
        Route::get('/', 'index')->name('index');

        // load more ajax
        Route::get('/load-more', 'loadMore')->name('load-more');

        // actions
        Route::post('/{id}/confirm', 'confirm')->name('confirm');
        Route::post('/{id}/cancel', 'cancel')->name('cancel');
        Route::post('/{id}/reschedule', 'reschedule')->name('reschedule');
    });
