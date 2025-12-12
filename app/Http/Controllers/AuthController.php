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
use Illuminate\Validation\Rules\Password as RulesPassword;

class AuthController extends Controller
{

    public function showSignup()
    {
        return view('auth.signup');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => [
                'required',
                'confirmed',
                RulesPassword::min(6)->mixedCase()->numbers()
            ],
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'language' => 'required|in:en,id',
            'terms' => 'required|accepted'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'preferred_language' => $request->language,
            'agree_to_terms' => true,
            'role' => 'user',
            'status' => 'active',
        ]);

        Auth::login($user);
        $user->sendOTPNotification();

        return redirect()->route('otp.verify')
            ->with('success', 'Registration successful! Please verify your email with OTP.');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 1. Cek Kredensial (Email & Password)
        if (Auth::attempt($credentials)) {

            $user = Auth::user(); // Ambil data user

            // 2. CEK STATUS (Logika Baru)
            if ($user->status !== 'active') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                $message = 'Your account is currently ' . $user->status . '.';
                if ($user->status === 'pending') {
                    $message .= ' Please wait for Admin approval.';
                }

                return back()->withErrors(['email' => $message])->onlyInput('email');
            }

            // 3. Jika Active, Lanjut Masuk
            $request->session()->regenerate();

            // Redirect sesuai role
            // Pastikan route ini ada di web.php (admin.dashboard / user.dashboard / psychologist.dashboard)
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'psychologist') {
                return redirect()->route('psychologist.dashboard');
            }

            return redirect()->route('patient.dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->onlyInput('email');
    }


    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', RulesPassword::min(6)->mixedCase()->numbers()],
        ]);

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

            'full_name' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => [
                'required',
                'confirmed',
                RulesPassword::min(8)->mixedCase()->numbers()
            ],
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'language' => 'required|in:en,id',
            'terms' => 'required|accepted',


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
            return back()->withErrors($validator)->withInput();
        }


        $user = User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'preferred_language' => $request->language,
            'agree_to_terms' => true,
            'role' => 'psychologist',
            'status' => 'pending',
        ]);


        $user->psychologist()->create([
            'title' => $request->title,
            'specialization' => $request->specialization,
            'license_number' => $request->license_number,
            'years_experience' => $request->years_experience,
            'consultation_fee' => $request->consultation_fee,
            'short_bio' => $request->short_bio,
            'about_me' => $request->about_me,
            'languages' => $request->input('languages'),
        ]);

        // Auth::login($user);
        // $user->sendOTPNotification();

        return redirect()->route('otp.verify')
            ->with('success', 'Registration successful! Please verify your email with OTP.');
    }
}
