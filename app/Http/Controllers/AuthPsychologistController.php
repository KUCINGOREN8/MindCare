<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password as RulesPassword;

class AuthPsychologistController extends Controller
{
    public function showStep1(Request $request)
    {
        $userId = $request->session()->get('psychologist_user_id');
        $user = $userId ? User::find($userId) : null;

        return view('auth.psychologist.step1', compact('user'));
    }

    public function storeStep1(Request $request)
    {
        $userId = $request->session()->get('psychologist_user_id');

        $rules = [
            'full_name' => 'required|string|min:3|max:255',
            'password' => ['required', 'confirmed', RulesPassword::min(6)->mixedCase()->numbers()],
            'date_of_birth' => [
                'required',
                'date',
                'before:today',
                function ($attribute, $value, $fail) {
                    $age = date_diff(date_create($value), date_create('today'))->y;
                    if ($age < 18) {
                        $fail('You must be at least 18 years old to register.');
                    }
                }
            ],
            'gender' => 'required|in:male,female,other',
            'preferred_language' => 'required|in:en,id',
            'terms' => 'required|accepted',
        ];

        if ($userId) {
            $rules['email'] = 'required|email|unique:users,email,' . $userId;
        } else {
            $rules['email'] = 'required|email|unique:users,email|max:255';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if ($userId) {
            $user = User::find($userId);
            $user->update([
                'full_name' => $request->full_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'preferred_language' => $request->preferred_language,
                'agree_to_terms' => true,
            ]);
        } else {
            $user = User::create([
                'full_name' => $request->full_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'preferred_language' => $request->preferred_language,
                'agree_to_terms' => true,
                'role' => 'psychologist',
                'status' => 'inactive',
                'otp_verified' => false,
            ]);
            $request->session()->put('psychologist_user_id', $user->id);
        }

        return redirect()->route('psychologist.signup.step2', ['user' => $user->id]);
    }

    // Step 2: Professional Info
    public function showStep2(User $user)
    {
        $psychologist = $user->psychologist;
        return view('auth.psychologist.step2', compact('user', 'psychologist'));
    }

    public function storeStep2(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'specialization' => 'required|string|max:500',
            'license_number' => 'required|string|unique:psychologists,license_number,' . ($user->psychologist->id ?? 'NULL'),
            'years_experience' => 'required|integer|min:0',
            'consultation_fee' => 'required|numeric|min:10000',
            'short_bio' => 'required|string|min:5|max:500',
            'about_me' => 'required|string|min:10',
            'languages' => 'required|array|min:1',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user->psychologist()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'title' => $request->title,
                'specialization' => $request->specialization,
                'license_number' => $request->license_number,
                'years_experience' => $request->years_experience,
                'consultation_fee' => $request->consultation_fee,
                'short_bio' => $request->short_bio,
                'about_me' => $request->about_me,
                'languages' => $request->input('languages'),
            ]
        );

        return redirect()->route('psychologist.signup.step3', $user);
    }

    // Step 3: Education
    public function showStep3(User $user)
    {
        $educations = $user->psychologist->educations ?? collect();
        return view('auth.psychologist.step3', compact('user', 'educations'));
    }

    public function storeStep3(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'educations' => 'required|array|min:1',
            'educations.*.degree' => 'required|string|max:255',
            'educations.*.institution' => 'required|string|max:255',
            'educations.*.year' => [
            'required',
            'digits:4',
            function ($attribute, $value, $fail) {
                $currentYear = date('Y');
                if ($value > $currentYear) {
                    $fail('The year cannot be in the future.');
                }
                if ($value < 1900) {
                    $fail('The year must be after 1900.');
                }
            }
        ],
        ], [
            'educations.*.year.digits' => 'Year must be exactly 4 digits',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', 'Please correct the education information.');
        }

        $user->psychologist->educations()->delete();
        $user->psychologist->educations()->createMany($request->educations);
        return redirect()->route('psychologist.signup.step4', $user);
    }

    // Step 4: Experience
    public function showStep4(User $user)
    {
        $experiences = $user->psychologist->experiences ?? collect();
        return view('auth.psychologist.step4', compact('user', 'experiences'));
    }

    public function storeStep4(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'experiences' => 'required|array|min:1',
            'experiences.*.position' => 'required|string|max:255',
            'experiences.*.organization' => 'required|string|max:255',
            'experiences.*.start_year' => [
            'required',
            'digits:4',
            function ($attribute, $value, $fail) {
                $currentYear = date('Y');
                if ($value > $currentYear) {
                    $fail('Start year cannot be in the future.');
                }
                if ($value < 1900) {
                    $fail('Start year must be after 1900.');
                }
            }
        ],
            'experiences.*.end_year' => [
            'nullable',
            'digits:4',
            function ($attribute, $value, $fail) use ($request) {
                if ($value) {
                    $currentYear = date('Y');
                    $index = explode('.', $attribute)[1];
                    $startYear = $request->input("experiences.{$index}.start_year");

                    if ($value > $currentYear) {
                        $fail('End year cannot be in the future.');
                    }
                    if ($value < 1900) {
                        $fail('End year must be after 1900.');
                    }
                    if ($value < $startYear) {
                        $fail('End year must be after start year.');
                    }
                }
            }
        ],
        ], [
        'experiences.*.start_year.digits' => 'Start year must be exactly 4 digits',
        'experiences.*.end_year.digits' => 'End year must be exactly 4 digits',
    ]);

    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput()->with('error', 'Please correct the experience information.');
    }
        $user->psychologist->experiences()->delete();
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
        $validated = $request->validate([
            'schedules' => 'required|array|min:1|max:7',
            'schedules.*.day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'schedules.*.start_time' => 'required|date_format:H:i',
            'schedules.*.end_time' => 'required|date_format:H:i|after:schedules.*.start_time',
        ]);

        $user->psychologist->schedules()->delete();
        $user->psychologist->schedules()->createMany($validated['schedules']);

        Auth()->login($user);
        $user->sendOTPNotification();

        return redirect()->route('otp.verify')->with('success', 'Registration successful! Please verify your email with OTP.');
    }
}
