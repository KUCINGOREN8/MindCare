<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    // SignUp
    public function showSignup()
    {
        return view('auth.signup');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|min:6|confirmed',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'language' => 'required|in:en,id',
            'terms' => 'required|accepted',
        ], [
            'full_name.required' => 'Full name is required.',
            'full_name.min' => 'Full name must be at least 3 characters.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
            'date_of_birth.required' => 'Date of birth is required.',
            'date_of_birth.before' => 'Date of birth must be in the past.',
            'gender.required' => 'Please select your gender.',
            'language.required' => 'Please select your preferred language.',
            'terms.required' => 'You must agree to the terms and conditions.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'preferred_language' => $request->language,
            'agree_to_terms' => true,
        ]);

        Auth::login($user);

        $user->sendOTPNotification();
        return redirect()->route('otp.verify')->with('success', 'Registration successful! Please verify your email with OTP.');
    }

    // Login
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        ], [
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'Password is required.',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard.index'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // Forgot Password
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ], [
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'You have been logged out successfully.');
    }

 
public function showSignupPsychologist()
{
    return view('auth.signup-psychologist');
}



public function registerPsychologist(Request $request)
{
    $validator = Validator::make($request->all(), [
        // USER VALIDATIONS
        'full_name' => 'required|string|min:3|max:255',
        'email' => 'required|email|unique:users|max:255',
        'password' => [
            'required',
            'confirmed',
            Password::min(8)->mixedCase()->numbers()
        ],
        'date_of_birth' => 'required|date|before:today',
        'gender' => 'required|in:male,female,other',
        'language' => 'required|in:en,id',
        'terms' => 'required|accepted',

        // PSYCHOLOGIST VALIDATIONS
        'title' => 'required|string|max:255',
        'specialization' => 'required|string|max:255',
        'license_number' => 'required|string|unique:psychologists,license_number',
        'years_experience' => 'required|integer|min:0',
        'consultation_fee' => 'required|numeric|min:0',
        'short_bio' => 'nullable|string',
        'about_me' => 'nullable|string',
        'languages' => 'nullable|array',
        'languages.*' => 'in:en,id',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    // CREATE USER (role: psychologist)
    $user = User::create([
        'full_name' => $request->full_name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'date_of_birth' => $request->date_of_birth,
        'gender' => $request->gender,
        'preferred_language' => $request->language,
        'agree_to_terms' => true,
        'role' => 'psychologist',
    ]);

    // CREATE PSYCHOLOGIST PROFILE
    $user->psychologist()->create([
        'title' => $request->title,
        'specialization' => $request->specialization,
        'license_number' => $request->license_number,
        'years_experience' => $request->years_experience,
        'consultation_fee' => $request->consultation_fee,
        'short_bio' => $request->short_bio,
        'about_me' => $request->about_me,
        'languages' => $request->input('languages') ? json_encode($request->input('languages')) : null,
    ]);

    // LOGIN & SEND OTP
    Auth::login($user);
    $user->sendOTPNotification();

    return redirect()->route('otp.verify')->with('success', 'Registration successful! Please verify your email with OTP.');
}


}
