<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Testimonial;
use App\Models\Mood;
use App\Models\Psychologist;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::inRandomOrder()->take(3)->get();
        return view('index', compact('testimonials'));
    }

    public function showPatientDashboard()
    {
        $user = Auth::user();

        $upcomingAppointments = Appointment::with(['psychologist' => function($query) {
                $query->with('user');
            }])
            ->where('user_id', $user->id)
            ->where('status', 'confirmed')
            ->where(function($query) {
                $query->where('date', '>', now()->format('Y-m-d'))->orWhere(function($q) {
                        $q->where('date', '=', now()->format('Y-m-d'))->whereTime('end_time', '>', now()->format('H:i:s'));
                    });
            })
            ->orderBy('date')
            ->orderBy('start_time')
            ->take(3)
            ->get();

        return view('dashboard.patient.index', compact('user', 'upcomingAppointments'));
    }

    public function showPsychologistDashboard()
    {
        $user = Auth::user();

        $upcomingAppointments = Appointment::with(['user'])
            ->where('psychologist_id', $user->id)
            ->where('status', 'confirmed')
            ->get()
            ->filter(function ($appointment) {
                return $appointment->is_upcoming;
            })
            ->sortBy('start_date_time')
            ->take(3);

        return view('dashboard.psychologist.index', compact('user', 'upcomingAppointments'));
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
