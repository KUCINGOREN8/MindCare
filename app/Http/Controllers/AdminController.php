<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\User as UserModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $notificationController = new NotificationController();
        $notifications = $notificationController->getNotifications();

        $users = User::where('role', '!=', 'psychologist')
            ->orWhere(function ($q) {
                $q->where('role', 'psychologist')->where('status', 'active');
            })
            ->latest()->paginate(10);

        return view('dashboard.admin.index', compact('user', 'users', 'notifications'));
    }

    // --- FITUR BARU: VERIFIKASI PSIKOLOG ---

    /**
     * 1. Menampilkan daftar psikolog yang statusnya masih 'pending'
     */
    public function verifyIndex()
    {
        $user = Auth::user();
        $notificationController = new NotificationController();
        $notifications = $notificationController->getNotifications();

        $pendingPsychologists = User::where('role', 'psychologist')
            ->where('status', 'pending')
            ->latest()
            ->paginate(10);

        return view('dashboard.admin.verify', compact('user', 'pendingPsychologists', 'notifications'));
    }

    public function approvePsychologist($id)
    {
        $psychologist = User::findOrFail($id);
        $psychologist->update([
            'status' => 'active'
        ]);

        return redirect()->back()->with('success', 'Psychologist registration has been approved.');
    }

    public function rejectPsychologist($id)
    {
        $psychologist = User::findOrFail($id);
        $psychologist->delete();

        return redirect()->back()->with('success', 'Psychologist registration has been rejected.');
    }

    public function create()
    {
        $user = Auth::user();
        $notificationController = new NotificationController();
        $notifications = $notificationController->getNotifications();

        return view('admin.create', compact('user', 'notifications'));
    }

    public function store(Request $request)
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
            'status' => 'active',
            'otp_verified' => true,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'User created successfully!');
    }

    public function show(string $id)
    {

    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $notificationController = new NotificationController();
        $notifications = $notificationController->getNotifications();

        return view('admin.edit', compact('user', 'notifications'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female',
            'language' => 'required|in:en,id',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $user->update([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'preferred_language' => $request->language,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.dashboard')->with('success', 'User updated successfully!');
    }

    public function destroy($id)
    {
        UserModel::where('id', $id)->delete();
        return redirect()->route('admin.dashboard')->with('success', 'User deleted successfully.');
    }
}
