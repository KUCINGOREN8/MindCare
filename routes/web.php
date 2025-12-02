<?php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PsychologistController;
use App\Http\Controllers\OTPController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MoodController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Middleware\OTPMiddleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


app('router')->aliasMiddleware('otp', OTPMiddleware::class);

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
// After Login (auth protected)
Route::middleware(['auth'])->group(function () {
    Route::get('/otp/send', [OTPController::class, 'sendOTP'])->name('otp.send');
    Route::get('/otp/verify', [OTPController::class, 'showVerifyForm'])->name('otp.verify');
    Route::post('/otp/verify', [OTPController::class, 'verifyOTP'])->name('otp.verify.post');
    Route::post('/otp/resend', [OTPController::class, 'resendOTP'])->name('otp.resend');
});

Route::middleware(['auth', 'otp'])->group(function () {
    // Dashboard Page
    Route::get('/dashboard' , [DashboardController::class, 'showDashboard'])->name('dashboard.index');
    Route::post('/mood', [DashboardController::class, 'moodStore'])->name('mood.store');
    Route::post('/mood/undo', [MoodController::class, 'undo'])->name('mood.undo');

    // Find Psychologist Page
    Route::get('find-psychologist', [PsychologistController::class, 'showFindPsychologist'])-> name('find.psychologist');
    Route::get('/search', [PsychologistController::class, 'showSearch'])->name('psychologist.search');
    Route::get('psychologist/{id}', [PsychologistController::class, 'showProfile'])-> name('psychologist.profile');
    Route::get('psychologist/{id}/review', [PsychologistController::class, 'showReview'])-> name('psychologist.review');

    // Book Appointment Page
    Route::get('book_appointment', function () {
        return view('pages.appointment.book');
    }) -> name('book.appointment');

    // Appointments Page
    Route::get('appointments', function () {
        return view('pages.appointment.history');
    }) -> name('appointments');

    // Messages Page
    Route::get('messages', function () {
        return view('pages.message.index');
    }) -> name('messages');

    // User Profile Page
    Route::get('/profile', [UserController::class, 'settings'])->name('profile.edit');
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // --- Logout ---
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Redirect root "/" → landing page
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/lang/{lang}', function ($lang) {
    session(['locale' => $lang]);
    return back();
})->name('switch.lang');
})->name('switch.lang');

// Route::get('/user/psychologist/dashboard', function () {
//     return view('pages.dashboard.index');
// });
