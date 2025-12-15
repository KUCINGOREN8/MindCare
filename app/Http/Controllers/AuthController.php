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
            'role' => 'patient',
            'status' => 'inactive',
            'otp_verified' => false,
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

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // 1. Simpan bahasa saat ini sebelum session dihapus
            $currentLocale = session('locale');

            // --- CEK STATUS USER ---
            if ($user->status !== 'active') {
                Auth::logout();

                // Reset session (Logout paksa)
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                if ($currentLocale) {
                    session(['locale' => $currentLocale]);
                }

                $message = __('auth.account_status', ['status' => $user->status]);

                if ($user->status === 'pending') {
                    $message .= ' ' . __('auth.wait_approval');
                }

                return back()->withErrors(['email' => $message])->onlyInput('email');
            }

            if (!$user->otp_verified) {
                return redirect()->route('otp.verify')
                    ->with('error', __('auth.otp_required'));
            }

            $request->session()->regenerate();

            if ($currentLocale) {
                session(['locale' => $currentLocale]);
            }

            // Redirect sesuai Role
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'psychologist') {
                return redirect()->route('psychologist.dashboard');
            }

            return redirect()->route('patient.dashboard');
        }

        return back()->withErrors([
            'email' => __('auth.failed'),
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
}
