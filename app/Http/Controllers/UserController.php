<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\PsychologistEducation;
use App\Models\PsychologistExperience;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password as RulesPassword;

class UserController extends Controller
{
    public function showProfile()
    {
        $user = Auth::user();

        // Load psychologist data (only if user role is psychologist)
        $psychologist = null;
        if ($user->isPsychologist()) {
            $psychologist = $user->psychologist()->with(['educations', 'experiences', 'schedules'])->first();
        }

        return view('settings.index', compact('user', 'psychologist'));
    }

    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $user = Auth::user();

        // Delete old photo if exists
        if ($user->photo_url) {
            $oldPath = $user->photo_url;
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        // Store new photo
        $path = $request->file('photo')->store('profile-photos', 'public');

        // Update user photo (simpan path relatif)
        $user->photo_url = $path;
        $user->save();

        return response()->json([
            'success' => true,
            'photo_url' => $user->photo_url,
            'message' => 'Photo profile updated successfully!'
        ]);
    }

    public function deletePhoto()
    {
        $user = Auth::user();

        // Delete photo from storage
        if ($user->photo_url && Storage::disk('public')->exists($user->photo_url)) {
            Storage::disk('public')->delete($user->photo_url);
        }

        // Set photo_url to null
        $user->photo_url = null;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Photo profile deleted successfully!'
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'gender' => 'required|in:male,female,other',
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
            'specialization' => 'required|string|max:500',
            'license_number' => 'required|string|unique:psychologists,license_number',
            'years_experience' => 'required|integer|min:0',
            'consultation_fee' => 'required|numeric|min:10000',
            'short_bio' => 'required|string|min:5|max:500',
            'about_me' => 'required|string|min:10',
            'languages' => 'required|array|min:1',
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

    public function storeEducation(Request $request)
    {
        $request->validate([
            'educations.*.degree' => 'required|string|max:255',
            'educations.*.institution' => 'required|string|max:255',
            'educations.*.year' => 'required|digits:4',
        ]);

        $psychologist = auth()->user()->psychologist;

        foreach ($request->educations as $educationData) {
            if (isset($educationData['id'])) {
                // Update existing
                $education = PsychologistEducation::find($educationData['id']);
                if ($education && $education->psychologist_id === $psychologist->id) {
                    $education->update($educationData);
                }
            } else {
                // Create new
                $psychologist->educations()->create($educationData);
            }
        }

        return redirect()->route('profile.index', ['tab' => 'professional'])->with('success', 'Education updated successfully.');
    }

    public function destroyEducation($id)
    {
        $education = PsychologistEducation::findOrFail($id);

        if ($education->psychologist_id !== auth()->user()->psychologist->id) {
            abort(403);
        }

        $education->delete();

        return response()->json(['success' => true]);
    }

    public function storeExperience(Request $request)
    {
        $request->validate([
            'experiences.*.position' => 'required|string|max:255',
            'experiences.*.organization' => 'required|string|max:255',
            'experiences.*.start_year' => 'required|string|max:4',
            'experiences.*.end_year' => 'nullable|string|max:4',
        ]);

        $psychologist = auth()->user()->psychologist;

        foreach ($request->experiences as $experienceData) {
            if (isset($experienceData['id'])) {
                // Update existing
                $experience = PsychologistExperience::find($experienceData['id']);
                if ($experience && $experience->psychologist_id === $psychologist->id) {
                    $experience->update($experienceData);
                }
            } else {
                // Create new
                $psychologist->experiences()->create($experienceData);
            }
        }

        return redirect()->route('profile.index', ['tab' => 'professional'])->with('success', 'Experience updated successfully.');
    }

    public function destroyExperience($id)
    {
        $experience = PsychologistExperience::findOrFail($id);

        if ($experience->psychologist_id !== auth()->user()->psychologist->id) {
            abort(403);
        }

        $experience->delete();

        return response()->json(['success' => true]);
    }

    public function updateSchedule(Request $request)
    {
        $user = Auth::user();

        if (!$user->isPsychologist()) {
            abort(403, 'Unauthorized access.');
        }

        $psychologist = $user->psychologist;

        $validator = Validator::make($request->all(), [
            'schedules.*.day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
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
            'old_password' => [
                'required',
                'string',
                function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        $fail('The old password is incorrect.');
                    }
                }
            ],
            'new_password' => [
                'required',
                'string',
                'confirmed',
                'different:old_password',
                RulesPassword::min(6)->mixedCase()->numbers()
            ],
        ], [
            'old_password.required' => 'Old password is required',
            'new_password.required' => 'New password is required',
            'new_password.confirmed' => 'Password confirmation does not match',
            'new_password.different' => 'New password must be different from old password',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the errors below.');
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
