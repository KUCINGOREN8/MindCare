<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function showProfile() {
        $user = Auth::user();

        return view('pages.settings.profile', compact('user'));
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
                ->with('error', 'Please fix the errors below.');
        }
        $user->update([
            'full_name' => $request->name,
            'email' => $request->email,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
        ]);

        return redirect()->route('profile')
            ->with('success', 'Profile updated successfully!');
    }
}
