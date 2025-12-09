<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\User as UserModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $user = Auth::user();
        $users = User::latest()->paginate(10);
        return view('pages.admin.index', compact('user'))->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $user = Auth::user();
        return view('pages.admin.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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

        return redirect()->route('admin.dashboard')->with('success', 'User created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
        $user = User::findOrFail($id);
        return view('pages.admin.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $user = User::findOrFail($id);

        // Validasi (Password dibuat nullable/opsional)
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id, // Ignore email milik sendiri saat cek unique
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female',
            'language' => 'required|in:en,id',
            'password' => 'nullable|min:6|confirmed', // Nullable: boleh kosong
        ]);

        // Update data dasar
        $user->update([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'preferred_language' => $request->language,
        ]);

        // Update password HANYA jika diisi
        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.dashboard')->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        UserModel::where('id', $id)->delete();
        return redirect()->route('admin.dashboard')->with('success', 'User deleted successfully.');
    }
}
