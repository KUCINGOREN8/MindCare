<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthPsychologistController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookAppointmentController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OTPController;
use App\Http\Controllers\PsychologistController;
use App\Http\Controllers\UserController;
// use App\Http\Controllers\MoodController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReviewController;
use App\Http\Middleware\OTPMiddleware;
use App\Http\Middleware\CheckAppointmentReview;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;

app('router')->aliasMiddleware('otp', OTPMiddleware::class);
app('router')->aliasMiddleware('role', CheckRole::class);
app('router')->aliasMiddleware('review', CheckAppointmentReview::class);

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

    // Psychologist multi-step signup
    Route::prefix('signup/psychologist')->name('psychologist.signup.')->group(function () {
        Route::get('/', [AuthPsychologistController::class, 'showStep1'])->name('step1');
        Route::post('/step1', [AuthPsychologistController::class, 'storeStep1'])->name('storeStep1');
        Route::get('/step2/{user}', [AuthPsychologistController::class, 'showStep2'])->name('step2');
        Route::post('/step2/{user}', [AuthPsychologistController::class, 'storeStep2'])->name('storeStep2');
        Route::get('/step3/{user}', [AuthPsychologistController::class, 'showStep3'])->name('step3');
        Route::post('/step3/{user}', [AuthPsychologistController::class, 'storeStep3'])->name('storeStep3');
        Route::get('/step4/{user}', [AuthPsychologistController::class, 'showStep4'])->name('step4');
        Route::post('/step4/{user}', [AuthPsychologistController::class, 'storeStep4'])->name('storeStep4');
        Route::get('/step5/{user}', [AuthPsychologistController::class, 'showStep5'])->name('step5');
        Route::post('/step5/{user}', [AuthPsychologistController::class, 'storeStep5'])->name('storeStep5');
    });

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

// PATIENT ROUTES => Auth + OTP + Role protected
Route::middleware(['auth', 'otp', 'role:patient'])
    ->prefix('patient')
    ->name('patient.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'showPatientDashboard'])->name('dashboard');

        // Patient Mood
        Route::post('/mood', [DashboardController::class, 'moodStore'])->name('mood.store');
        // Route::post('/mood/undo', [MoodController::class, 'undo'])->name('mood.undo');

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
            Route::get('/{appointment}/payment', 'showPaymentPage')->name('payment');

            Route::put('/{appointment}/reschedule', 'reschedule')->name('reschedule');
            Route::post('/{appointment}/confirm', 'confirm')->name('confirm');
            Route::post('/{appointment}/cancel', 'cancel')->name('cancel');

            Route::get('/chat/session/{appointment}', [ChatController::class, 'startSession'])->name('chat.session');

            // Appointment Review
            Route::middleware(['review:create'])->group(function () {
                Route::get('/{id}/review', [ReviewController::class, 'create'])->name('review.create');
                Route::post('/{id}/review', [ReviewController::class, 'store'])->name('review.store');                
            });
            Route::middleware(['review:edit'])->group(function () {          
                Route::get('/{id}/review/edit', [ReviewController::class, 'edit'])->name('review.edit');
                Route::put('/{id}/review', [ReviewController::class, 'update'])->name('review.update');
            });
            Route::middleware(['review:delete'])->group(function () {
                Route::delete('/{id}/review', [ReviewController::class, 'destroy'])->name('review.destroy');
            });
            
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
        Route::get('/dashboard', [DashboardController::class, 'showPsychologistDashboard'])->name('dashboard');

    // My Clients
    Route::get('/clients' , [PsychologistController::class, 'showClients'])->name('clients');
    Route::get('/clients/{client}/details', [PsychologistController::class, 'showClientDetails'])->name('clients.details');
    Route::get('/clients/{client}/appointments', [PsychologistController::class, 'getClientAppointments'])->name('clients.appointments');
    Route::get('/clients/{client}/general-notes', [PsychologistController::class, 'getGeneralNotes'])->name('clients.general-notes');
    Route::post('/notes/general/store', [PsychologistController::class, 'storeGeneralNotes'])->name('notes.general.store');
    Route::post('/notes/store', [PsychologistController::class, 'storeSessionNotes'])->name('notes.session.store');

        // Appointments
        Route::prefix('appointments')
        ->name('appointments.')
        ->controller(AppointmentController::class)
        ->group(function () {
            Route::get('/', [PsychologistController::class, 'showSchedule'])->name('index');
            Route::get('/chat/session/{appointment}', [ChatController::class, 'startSession'])->name('chat.session');
    });
});

// ADMIN ROUTES => Auth + OTP + Role protected
Route::middleware(['auth', 'otp', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/verify-psychologists', [AdminController::class, 'verifyIndex'])->name('verify.index');
        Route::post('/verify-psychologists/{id}/approve', [AdminController::class, 'approvePsychologist'])->name('verify.approve');
        Route::delete('/verify-psychologists/{id}/reject', [AdminController::class, 'rejectPsychologist'])->name('verify.reject');

        // Manage User (CRUD)
        Route::resource('users', AdminController::class);
    });

// SHARED ROUTES -> Auth + OTP Protected
Route::middleware(['auth', 'otp'])->group(function () {
    // Settings
    Route::prefix('profile')->name('profile.')->controller(UserController::class)->group(function () {
        Route::get('/', 'showProfile')->name('index');

        // Profile 
        Route::put('/update','updateProfile')->name('update');
        Route::post('/upload-photo', 'uploadPhoto')->name('upload-photo');
        Route::delete('/delete-photo', 'deletePhoto')->name('delete-photo');

        // Privacy
        Route::put('/password', 'updatePrivacy')->name('privacy.update');

        // Preferences
        Route::put('/preferences', 'updatePreferences')->name('preferences.update');

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

