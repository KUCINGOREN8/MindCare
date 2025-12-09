<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function showProfile() {
        $user = Auth::user();
        
        // Load psychologist data (only if user role is psychologist)
        $psychologist = null;
        if ($user->isPsychologist()) {
            $psychologist = $user->psychologist()->with(['educations', 'experiences', 'schedules'])->first();
        }
        
        return view('settings.index', compact('user', 'psychologist'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'gender' => 'nullable|string|in:male,female,other',
            'date_of_birth' => 'nullable|date|before:today',
        ], [
            'date_of_birth.date' => 'Please enter a valid date',
            'date_of_birth.before' => 'Date of birth must be in the past',
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the errors.');
        }
        $user->update([
            'full_name' => $request->name,
            'email' => $request->email,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
        ]);

        return redirect()->route('profile.index', ['tab' => 'profile'])
            ->with('success', 'Profile updated successfully!');
    }

    public function updateProfessional(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isPsychologist()) {
            abort(403, 'Unauthorized access.');
        }
        
        $psychologist = $user->psychologist;
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'license_number' => 'required|string|max:100',
            'years_experience' => 'required|integer|min:0',
            'consultation_fee' => 'required|numeric|min:0',
            'short_bio' => 'nullable|string|max:500',
            'about_me' => 'nullable|string',
            'languages' => 'nullable|array',
            'languages.*' => 'string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the errors.');
        }
        
        $psychologist->update([
            'title' => $request->title,
            'specialization' => $request->specialization,
            'license_number' => $request->license_number,
            'years_experience' => $request->years_experience,
            'consultation_fee' => $request->consultation_fee,
            'short_bio' => $request->short_bio,
            'about_me' => $request->about_me,
            'languages' => $request->languages,
        ]);
        
        return redirect()->route('profile.index', ['tab' => 'professional'])
            ->with('success', 'Professional information updated successfully!');
    }

    public function updateSchedule(Request $request)
    {
        $user = Auth::user();
    
        if (!$user->isPsychologist()) {
            abort(403, 'Unauthorized access.');
        }
        
        $psychologist = $user->psychologist;

        $validator = Validator::make($request->all(), [
            'schedules.*.day_of_week' => 'required|string|in:mon,tue,wed,thu,fri,sat,sun',
            'schedules.*.is_available' => 'nullable|in:1,0',
            'schedules.*.start_time' => 'required_if:schedules.*.is_available,1|nullable|date_format:H:i',
            'schedules.*.end_time' => 'required_if:schedules.*.is_available,1|nullable|date_format:H:i|after:schedules.*.start_time',
        ], [
            'schedules.*.start_time.required_if' => 'Start time is required when day is available',
            'schedules.*.end_time.required_if' => 'End time is required when day is available',
            'schedules.*.end_time.after' => 'End time must be after start time',
        ]);
        
        $validator->after(function ($validator) use ($request) {
            $hasAvailableDay = false;
            if ($request->has('schedules')) {
                foreach ($request->schedules as $schedule) {
                    if (isset($schedule['is_available']) && $schedule['is_available'] == '1') {
                        $hasAvailableDay = true;
                        break;
                    }
                }
            }
            
            if (!$hasAvailableDay) {
                $validator->errors()->add('schedules', 'Please set at least one available day');
            }
        });
        
        if ($validator->fails()) {
            return redirect()->route('profile.index', ['tab' => 'schedule'])
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the errors below.');
        }
        
        $psychologist->schedules()->delete();
        
        foreach ($request->schedules as $scheduleData) {
            if (isset($scheduleData['is_available']) && $scheduleData['is_available'] == '1') {
                $psychologist->schedules()->create([
                    'day_of_week' => $scheduleData['day_of_week'],
                    'start_time' => $scheduleData['start_time'],
                    'end_time' => $scheduleData['end_time'],
                ]);
            }
        }
        
        return redirect()->route('profile.index', ['tab' => 'schedule'])
            ->with('success', 'Schedule updated successfully!');
    }

    public function updatePrivacy(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:6|different:old_password',
            'confirm_password' => 'required|string|same:new_password',
        ], [
            'old_password.required' => 'Old password is required',
            'new_password.required' => 'New password is required',
            'new_password.min' => 'Password must be at least 6 characters',
            'new_password.different' => 'New password must be different from old password',
            'confirm_password.required' => 'Please confirm your new password',
            'confirm_password.same' => 'Passwords do not match',
        ]);
        
        $validator->after(function ($validator) use ($user, $request) {
            if (!Hash::check($request->old_password, $user->password)) {
                $validator->errors()->add('old_password', 'Old password is incorrect');
            }
        });
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the errors.');
        }
        
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);
        
        return redirect()->route('profile.index', ['tab' => 'privacy'])
            ->with('success', 'Password updated successfully!');
    }

    public function updatePreferences(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'language' => 'required|string|in:en,id',
        ], [
            'language.required' => 'Please select a preferred language',
            'language.in' => 'Please select a valid language',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the errors.');
        }
        
        $user->update([
            'preferred_language' => $request->language,
        ]);
        
        session()->put('locale', $request->language);
        
        return redirect()->route('profile.index', ['tab' => 'preferences'])
            ->with('success', 'Preferences updated successfully!');
    }
}
