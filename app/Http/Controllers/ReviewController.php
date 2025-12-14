<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\PsychologistReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // Show create review form
    public function create($appointmentId)
    {
        $appointment = Appointment::with(['psychologist.user', 'user'])
            ->where('id', $appointmentId)
            ->where('user_id', Auth::id())
            ->where('status', 'completed')
            ->firstOrFail();
        
        // Check if already reviewed
        $existingReview = PsychologistReview::where([
            'user_id' => Auth::id(),
            'psychologist_id' => $appointment->psychologist_id,
        ])->first();
        
        return view('patient.review.create', [
            'appointment' => $appointment,
            'existingReview' => $existingReview,
        ]);
    }
    
    // Store review
    public function store(Request $request, $appointmentId)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|between:1,5',
            'review' => 'nullable|string|max:1000',
        ]);
        
        $appointment = Appointment::with('psychologist')
            ->where('id', $appointmentId)
            ->where('user_id', Auth::id())
            ->where('status', 'completed')
            ->firstOrFail();
        
        // Check if already reviewed
        $existingReview = PsychologistReview::where([
            'user_id' => Auth::id(),
            'psychologist_id' => $appointment->psychologist_id,
        ])->first();
        
        if ($existingReview) {
            return redirect()
                ->route('patient.appointments.index')
                ->with('error', 'You have already reviewed this psychologist.');
        }
        
        // Create review
        $review = PsychologistReview::create([
            'psychologist_id' => $appointment->psychologist_id,
            'user_id' => Auth::id(),
            'rating' => $validated['rating'],
            'review' => $validated['review'],
        ]);
        
        return redirect()
            ->route('patient.appointments.index')
            ->with('success', 'Thank you for your review!');
    }
    
    // Show edit review form
    public function edit($appointmentId)
    {
        $appointment = Appointment::with(['psychologist.user', 'user'])
            ->where('id', $appointmentId)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        $review = PsychologistReview::where([
            'user_id' => Auth::id(),
            'psychologist_id' => $appointment->psychologist_id,
        ])->firstOrFail();
        
        return view('patient.review.edit', [
            'appointment' => $appointment,
            'review' => $review,
        ]);
    }
    
    // Update review
    public function update(Request $request, $appointmentId)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|between:1,5',
            'review' => 'nullable|string|max:1000',
        ]);
        
        $appointment = Appointment::with('psychologist')
            ->where('id', $appointmentId)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        $review = PsychologistReview::where([
            'user_id' => Auth::id(),
            'psychologist_id' => $appointment->psychologist_id,
        ])->firstOrFail();
        
        $review->update([
            'rating' => $validated['rating'],
            'review' => $validated['review'],
        ]);
        
        return redirect()
            ->route('patient.appointments.index')
            ->with('success', 'Review updated successfully.');
    }
    
    // Delete review
    public function destroy($appointmentId)
    {
        $appointment = Appointment::with('psychologist')
            ->where('id', $appointmentId)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        $review = PsychologistReview::where([
            'user_id' => Auth::id(),
            'psychologist_id' => $appointment->psychologist_id,
        ])->firstOrFail();
        
        $review->delete();
        
        return redirect()
            ->route('patient.appointments.index')
            ->with('success', 'Review deleted successfully.');
    }
}