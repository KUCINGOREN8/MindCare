<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Testimonial;
use App\Models\Mood;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::inRandomOrder()->take(3)->get();

        return view('index', compact('testimonials'));
    }

    public function showDashboard() {
        $user = Auth::user();

        // $upcomingAppointments
        return view('pages.dashboard.index', compact('user'));
    }

    public function moodStore(Request $request)
    {
        $request->validate([
            'mood' => ['required', Rule::in(['sad', 'flat', 'good', 'happy', 'blissful'])],
        ]);

        $mood = Mood::create([
            'user_id' => auth()->id(),
            'mood' => $request->mood,
        ]);

        return back()->with('success', 'Mood berhasil disimpan!')
                     ->with('undo_id', $mood->id);
    }

    public function undo(Request $request)
    {
        $mood = Mood::find($request->undo_id);

        if ($mood && $mood->user_id == auth()->id()) {
            $mood->delete();
            return back()->with('success', 'Mood berhasil dibatalkan.');
        }

        return back()->with('error', 'Mood tidak ditemukan atau tidak bisa dibatalkan.');
    }
}
