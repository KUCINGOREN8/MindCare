<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Psychologist;
use App\Models\PsychologistEducation;
use App\Models\PsychologistExperience;
use App\Models\PsychologistSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password as RulesPassword;

class AuthPsychologistController extends Controller
{
    // Step 1: Basic Info & Credentials
    public function showStep1()
    {
        return view('auth.psychologist.step1');
    }

    public function storeStep1(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => ['required', 'confirmed', RulesPassword::min(8)->mixedCase()->numbers()],
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'preferred_language' => 'required|in:en,id',
            'terms' => 'required|accepted',
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
            'preferred_language' => $request->preferred_language,
            'agree_to_terms' => true,
            'role' => 'psychologist',
            'status' => 'pending',
        ]);

        return redirect()->route('psychologist.signup.step2', ['user' => $user->id]);
    }

    // Step 2: Professional Info
    public function showStep2(User $user)
    {
        return view('auth.psychologist.step2', compact('user'));
    }

    public function storeStep2(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'specialization' => 'required|string|max:500',
            'license_number' => 'required|string|unique:psychologists,license_number',
            'years_experience' => 'required|integer|min:0',
            'consultation_fee' => 'required|numeric|min:10000',
            'short_bio' => 'required|string|min:50|max:500',
            'about_me' => 'required|string|min:100',
            'languages' => 'required|array|min:1',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $psychologist = $user->psychologist()->create([
            'title' => $request->title,
            'specialization' => $request->specialization,
            'license_number' => $request->license_number,
            'years_experience' => $request->years_experience,
            'consultation_fee' => $request->consultation_fee,
            'short_bio' => $request->short_bio,
            'about_me' => $request->about_me,
            'languages' => $request->input('languages'),
        ]);

        return redirect()->route('psychologist.signup.step3', $user);
    }

    // Step 3: Education
    public function showStep3(User $user)
    {
        return view('auth.psychologist.step3', compact('user'));
    }

    public function storeStep3(Request $request, User $user)
    {
        $request->validate([
            'educations' => 'required|array|min:1',
            'educations.*.degree' => 'required|string|max:255',
            'educations.*.institution' => 'required|string|max:255',
            'educations.*.year' => 'required|digits:4',
        ]);

        $user->psychologist->educations()->createMany($request->educations);
        return redirect()->route('psychologist.signup.step4', $user);
    }

    // Step 4: Experience
    public function showStep4(User $user)
    {
        return view('auth.psychologist.step4', compact('user'));
    }

    public function storeStep4(Request $request, User $user)
    {
        $request->validate([
            'experiences' => 'required|array|min:1',
            'experiences.*.position' => 'required|string|max:255',
            'experiences.*.organization' => 'required|string|max:255',
            'experiences.*.start_year' => 'required|string|max:4',
            'experiences.*.end_year' => 'nullable|string|max:4',
        ]);

        $user->psychologist->experiences()->createMany($request->experiences);
        return redirect()->route('psychologist.signup.step5', $user);
    }

    // Step 5: Schedule
    public function showStep5(User $user)
    {
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        return view('auth.psychologist.step5', compact('user', 'days'));
    }

    public function storeStep5(Request $request, User $user)
    {
        $request->validate([
            'schedules' => 'required|array|min:1',
            'schedules.*.day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'schedules.*.start_time' => 'required|date_format:H:i',
            'schedules.*.end_time' => 'required|date_format:H:i|after:schedules.*.start_time',
        ]);

        $user->psychologist->schedules()->createMany($request->schedules);

        // Login user dan kirim OTP
        auth()->login($user);
        $user->sendOTPNotification();
        $user->save();

        return redirect()->route('otp.verify')
            ->with('success', 'Registration data saved! Please check your email for the OTP code.');
    }

    // public function complete()
    // {
    //     return view('auth.psychologist.complete');
    // }
}
