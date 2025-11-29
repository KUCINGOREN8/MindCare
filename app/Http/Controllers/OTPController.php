<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OTPCodeMail;

class OTPController extends Controller
{
    public function sendOTP()
    {
        $user = Auth::user();

        $otpCode = $user->generateOTP();

        Mail::to($user->email)->send(new OTPCodeMail($otpCode));

        return redirect()->route('otp.verify')
            ->with('success', 'OTP code has been sent to your email!');
    }

    public function showVerifyForm()
    {
        return view('auth.otp-verify');
    }

    public function verifyOTP(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|digits:6'
        ]);

        $user = Auth::user();

        if ($user->isOTPValid($request->otp_code)) {
            $user->update([
                'otp_verified' => true,
                'otp_code' => null,
                'otp_expires_at' => null
            ]);

            session(['otp_verified' => true]);
            return redirect()->intended(route('dashboard.index'))
                ->with('success', 'OTP verified successfully!');
        }

        return back()->withErrors(['otp_code' => 'Invalid or expired OTP code.']);
    }

    public function resendOTP()
    {
        $user = Auth::user();
        $otpCode = $user->generateOTP();

        Mail::to($user->email)->send(new OTPCodeMail($otpCode));

        return back()->with('success', 'New OTP code has been sent!');
    }
}
