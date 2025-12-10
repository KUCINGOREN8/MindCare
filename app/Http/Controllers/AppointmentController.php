<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $this->autoCompletePastAppointments($user->id);

        $confirmedAppointments = Appointment::with(['psychologist' => function($query) {
            $query->with('user');
        }])
        ->where('user_id', $user->id)
        ->where('status', 'confirmed')
        ->get();

        $ongoing = $confirmedAppointments
            ->filter(function($appointment) {
                return $appointment->is_upcoming;
            })
            ->sortBy('start_date_time')
            ->take(3);

        $ongoingIds = $ongoing->pluck('id')->toArray();

        $history = Appointment::with(['psychologist' => function($query) {
                $query->with('user');
            }])
            ->where('user_id', $user->id)
            ->whereNotIn('id', $ongoingIds)
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();

        $rescheduleRequests = Appointment::with(['psychologist' => function($query) {
                $query->with('user');
            }])
            ->where('user_id', $user->id)
            ->whereNotNull('reschedule_date')
            ->where('status', '!=', 'cancelled')
            ->orderBy('reschedule_date')
            ->orderBy('reschedule_time')
            ->get();

        return view('patient.appointment.appointments', compact('ongoing', 'history', 'rescheduleRequests'));
    }

    private function autoCompletePastAppointments($userId)
    {
       $appointments = Appointment::where('user_id', $userId)->where('status', 'confirmed')->get();

       foreach ($appointments as $appointment) {
        if ($appointment->is_past) {
            $appointment->update(['status' => 'completed']);
        }
    }
       return $appointments->where('is_past', true)->count();
    }

    public function confirm($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->status = 'confirmed';
        $appointment->save();

        return back();
    }

    public function cancel($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->status = 'canceled';
        $appointment->save();

        return back();
    }

    // public function reschedule(Request $request, $id)
    // {
    //     $appointment = Appointment::findOrFail($id);

    //     $appointment->reschedule_date = $request->reschedule_date;
    //     $appointment->reschedule_time = $request->reschedule_time;
    //     $appointment->reschedule_reason = $request->reschedule_reason;

    //     $appointment->save();

    //     return view('profile.appointments', compact('ongoing', 'history'));
    // }

}
