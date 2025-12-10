<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (strtolower(trim($user->role)) === 'psychologist') {
            
            
            $ongoing = Appointment::with('user')
                ->where('psychologist_id', $user->id)
                ->where('status', 'confirmed') 
                ->where(function ($query) {
                    $query->where('date', '>', Carbon::now()->format('Y-m-d'))
                          ->orWhere(function ($q) {
                              $q->where('date', '=', Carbon::now()->format('Y-m-d'))
                                ->where('start_time', '>=', Carbon::now()->format('H:i:s'));
                          });
                })
                ->orderBy('date', 'asc')
                ->orderBy('start_time', 'asc') 
                ->first();

            $history = Appointment::with('user')
                ->where('psychologist_id', $user->id)
                ->where(function ($query) {
                    $query->where('status', 'completed')
                          ->orWhere('status', 'cancelled')
                          ->orWhere('status', 'canceled')
                          ->orWhere('date', '<', Carbon::now()->format('Y-m-d'));
                })
                ->orderBy('date', 'desc')
                ->orderBy('start_time', 'desc') 
                ->get();

            return view('psychologist.appointment.index', compact('ongoing', 'history'));
        }



        
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

    // --- FUNCTION PENDUKUNG ---

    private function autoCompletePastAppointments($userId)
    {
       $appointments = Appointment::where('user_id', $userId)->where('status', 'confirmed')->get();

       foreach ($appointments as $appointment) {
            if ($appointment->is_past ?? false) { 
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
}