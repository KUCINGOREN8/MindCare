
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
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\OTPMiddleware;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;

app('router')->aliasMiddleware('otp', OTPMiddleware::class);
app('router')->aliasMiddleware('role', CheckRole::class);

// Redirect root "/" → landing page
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Language switcher
Route::get('/lang/{lang}', function ($lang) {
    session(['locale' => $lang]);
    return back();
})->name('switch.lang');

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

    Route::get('/signup/psychologist', [AuthController::class, 'showSignupPsychologist'])->name('signup.psychologist');
    Route::post('/signup/psychologist', [AuthController::class, 'registerPsychologist'])->name('register.psychologist');
});

// OTP Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/otp/send', [OTPController::class, 'sendOTP'])->name('otp.send');
    Route::get('/otp/verify', [OTPController::class, 'showVerifyForm'])->name('otp.verify');
    Route::post('/otp/verify', [OTPController::class, 'verifyOTP'])->name('otp.verify.post');
    Route::post('/otp/resend', [OTPController::class, 'resendOTP'])->name('otp.resend');
});

// PATIENT ROUTES => Auth + OTP + Role protected
Route::middleware(['auth', 'otp', 'role:patient'])
->prefix('patient')
->name('patient.')
->group(function () {
    // Dashboard
    Route::get('/dashboard' , [DashboardController::class, 'showPatientDashboard'])->name('dashboard');

    // Patient Mood
    Route::post('/mood', [DashboardController::class, 'moodStore'])->name('mood.store');
    Route::post('/mood/undo', [MoodController::class, 'undo'])->name('mood.undo');

    // Psychologist
    Route::get('find-psychologist', [PsychologistController::class, 'showFindPsychologist'])->name('find.psychologist');
    Route::get('/search', [PsychologistController::class, 'showSearch'])->name('psychologist.search');
    Route::get('psychologist/{id}', [PsychologistController::class, 'showProfile'])->name('psychologist.profile');
    Route::get('psychologist/{id}/review', [PsychologistController::class, 'showReview'])->name('psychologist.review');
    Route::get('psychologist/{psychologist}/available-dates', [BookAppointmentController::class, 'getAvailableDates'])->name('psychologist.available-dates');
    Route::get('psychologist/{psychologist}/available-times', [BookAppointmentController::class, 'getAvailableTimes'])->name('psychologist.available-times');
    Route::get('available-psychologists', [BookAppointmentController::class, 'getAvailablePsychologists'])->name('psychologists.available');
    Route::get('psychologist/{psychologist}/available-days', [BookAppointmentController::class, 'getAvailableDays'])->name('psychologist.available-days');
    Route::get('/appointments/{appointment}/payment', [AppointmentController::class, 'showPaymentPage'])->name('appointments.payment');

    //Book Appointment
    Route::get('book-appointment/{psychologist?}', [BookAppointmentController::class, 'showBook'])->name('book.appointment');
    Route::post('/appointments/store', [BookAppointmentController::class, 'store'])->name('appointments.store');

    // Appointment History + Actions
    Route::prefix('appointments')
        ->name('appointments.')
        ->controller(AppointmentController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/{id}/confirm', 'confirm')->name('confirm');
            Route::post('/{id}/cancel', 'cancel')->name('cancel');
            Route::post('/{id}/reschedule', 'reschedule')->name('reschedule');
            Route::get('/chat/session/{appointment}', [ChatController::class, 'startSession'])->name('chat.session');
    });

    // Payment
    Route::prefix('payment')->name('payment.')->group(function () {
        Route::get('/process/{payment}', [PaymentController::class, 'process'])->name('process');
        Route::get('/finish', [PaymentController::class, 'finish'])->name('finish');
        Route::get('/error', [PaymentController::class, 'error'])->name('error');
        Route::get('/pending', [PaymentController::class, 'pending'])->name('pending');
        Route::post('/webhook', [PaymentController::class, 'webhook'])->name('webhook');
    });
});

// PSYCHOLOGIST ROUTES => Auth + OTP + Role protected
Route::middleware(['auth', 'otp', 'role:psychologist'])
->prefix('psychologist')
->name('psychologist.')
->group(function () {
    // Dashboard
    Route::get('/dashboard' , [DashboardController::class, 'showPsychologistDashboard'])->name('dashboard');

    // My Clients
    Route::get('/clients' , [PsychologistController::class, 'showClients'])->name('clients');

    // Appointments
        Route::prefix('appointments')
        ->name('appointments.')
        ->controller(AppointmentController::class)
        ->group(function () {
            // TO-DO: GANTI ROUTE DISINI KALAU PAGE NYA UDH
            Route::get('/', 'index')->name('index');
    });
});

// ADMIN ROUTES => Auth + OTP + Role protected
Route::middleware(['auth', 'otp', 'role:admin'])
->prefix('admin')
->name('admin.')
->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // Manage User (CRUD)
    Route::resource('users', AdminController::class);
});

// SHARED ROUTES -> Auth + OTP Protected
Route::middleware(['auth', 'otp'])->group(function () {
    // Settings Profile
    Route::prefix('profile')->name('profile.')->controller(UserController::class)->group(function () {
        Route::get('/', 'showProfile')->name('index');
        Route::put('/update','updateProfile')->name('update');
        Route::put('/profile/password', 'updatePrivacy')->name('privacy.update');
        Route::put('/profile/preferences', 'updatePreferences')->name('preferences.update');

        // Accessible for psychologist only
        Route::middleware('role:psychologist')->group(function () {
            // Professional Info
            Route::put('/professional', 'updateProfessional')->name('professional.update');

            // Education
            Route::post('/education', [UserController::class, 'storeEducation'])->name('education.store');
            Route::delete('education/{id}', [UserController::class, 'destroyEducation'])->name('education.destroy');
            
            // Experience
            Route::post('/experience', [UserController::class, 'storeExperience'])->name('experience.store');
            Route::delete('/experience/{id}', [UserController::class, 'destroyExperience'])->name('experience.destroy');
            
            // Schedule
            Route::put('/schedule', 'updateSchedule')->name('schedule.update');

        });
    });

    // Messages
    Route::get('/messages', [ChatController::class, 'index'])->name('messages');
    Route::get('/chat/start/{psychologist}', [ChatController::class, 'startChat'])->name('chat.start');
    Route::get('/chat/conversation/{conversation}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/conversation/{conversation}/send', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('/chat/conversation/{conversation}/messages', [ChatController::class, 'getMessages'])->name('chat.messages');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

