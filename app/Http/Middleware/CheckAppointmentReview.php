<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Appointment;
use App\Models\PsychologistReview;

class CheckAppointmentReview
{
    public function handle(Request $request, Closure $next, string $type = 'create'): Response
    {
        $appointmentId = $request->route('id') ?? $request->route('appointment');
        
        if (!$appointmentId) {
            abort(404, 'Appointment not found');
        }
        
        $appointment = Appointment::findOrFail($appointmentId);

        if (auth()->id() !== $appointment->user_id) {
            abort(403, 'Not authorized to review this appointment.');
        }

        if ($appointment->status !== 'completed') {
            abort(403, 'Only completed appointments can be reviewed.');
        }

        $hasReviewed = PsychologistReview::where([
            'user_id' => auth()->id(),
            'psychologist_id' => $appointment->psychologist_id,
        ])->exists();
        
        if ($type === 'create' && $hasReviewed) {
            abort(403, 'You have already reviewed this psychologist.');
        }
        
        if ($type === 'edit' && !$hasReviewed) {
            abort(403, 'You have not reviewed this psychologist yet.');
        }

        if ($type === 'delete') {
            $hasReviewed = PsychologistReview::where([
                'user_id' => auth()->id(),
                'psychologist_id' => $appointment->psychologist_id,
            ])->exists();
            
            if (!$hasReviewed) {
                abort(403, 'You have not reviewed this psychologist yet.');
            }
        }
        
        return $next($request);
    }
}
