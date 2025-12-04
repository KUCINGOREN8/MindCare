<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookAppointmentController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OTPController;
use App\Http\Controllers\PsychologistController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MoodController;
use App\Http\Middleware\OTPMiddleware;
use Illuminate\Support\Facades\Route;

app('router')->aliasMiddleware('otp', OTPMiddleware::class);

// Public guest-only routes
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

// OTP Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/otp/send', [OTPController::class, 'sendOTP'])->name('otp.send');
    Route::get('/otp/verify', [OTPController::class, 'showVerifyForm'])->name('otp.verify');
    Route::post('/otp/verify', [OTPController::class, 'verifyOTP'])->name('otp.verify.post');
    Route::post('/otp/resend', [OTPController::class, 'resendOTP'])->name('otp.resend');
});

// Auth + OTP protected
Route::middleware(['auth', 'otp'])->group(function () {

    // Dashboard
    Route::get('/dashboard' , [DashboardController::class, 'showDashboard'])->name('dashboard.index');
    Route::post('/mood', [DashboardController::class, 'moodStore'])->name('mood.store');
    Route::post('/mood/undo', [MoodController::class, 'undo'])->name('mood.undo');

    // Psychologist
    Route::get('find-psychologist', [PsychologistController::class, 'showFindPsychologist'])->name('find.psychologist');
    Route::get('/search', [PsychologistController::class, 'showSearch'])->name('psychologist.search');
    Route::get('psychologist/{id}', [PsychologistController::class, 'showProfile'])->name('psychologist.profile');
    Route::get('psychologist/{id}/review', [PsychologistController::class, 'showReview'])->name('psychologist.review');

    //Book Appointment 
    Route::get('book_appointment', [BookAppointmentController::class, 'showBook'])->name('book.appointment');
    Route::post('/appointments/store', [BookAppointmentController::class, 'store'])->name('appointments.store');

    // Appointment History + Actions
    Route::prefix('appointments')
        ->name('appointments.')
        ->controller(AppointmentController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/load-more', 'loadMore')->name('load-more');
            Route::post('/{id}/confirm', 'confirm')->name('confirm');
            Route::post('/{id}/cancel', 'cancel')->name('cancel');
            Route::post('/{id}/reschedule', 'reschedule')->name('reschedule');
    });

    // Messages
    Route::get('/messages', [ChatController::class, 'index'])->name('messages');
    Route::get('/chat/start/{psychologist}', [ChatController::class, 'startChat'])->name('chat.start');
    Route::get('/chat/conversation/{conversation}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/conversation/{conversation}/send', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('/chat/conversation/{conversation}/messages', [ChatController::class, 'getMessages'])->name('chat.messages');

    // User Profile
    Route::get('/profile', [UserController::class, 'settings'])->name('profile.edit');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Redirect root "/" → landing page
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Language switcher
Route::get('/lang/{lang}', function ($lang) {
    session(['locale' => $lang]);
    return back();
})->name('switch.lang');
