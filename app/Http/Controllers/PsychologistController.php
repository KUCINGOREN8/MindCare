<?php

namespace App\Http\Controllers;

use App\Models\Psychologist;
use App\Models\Appointment;
use App\Models\PsychologistClientNote;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PsychologistController extends Controller
{

    public function showFindPsychologist()
    {
        $psychologists = Psychologist::with('user')->whereHas('user', function($query) {
            $query->where('otp_verified', true)
                ->where('status', 'active')
                ->where('role', 'psychologist');
        })
        ->get();

        return view('patient.psychologist.find', compact('psychologists'));
    }

    public function showProfile($id) {
        $psychologist = Psychologist::with([
            'user',
            'educations',
            'experiences' => function ($q) {
                $q->orderBy('start_year', 'desc');
            },
            'schedules',
            'reviews' => function ($query) {
                $query->latest();
            }])
            ->whereHas('user', function($query) {
            $query->where('otp_verified', true)
                ->where('status', 'active');
            })
        ->findOrFail($id);

        return view('patient.psychologist.profile', compact('psychologist'));
    }

    public function showSearch(Request $request) {

        $query = $request->input('q');

         $results = Psychologist::with('user')
            ->whereHas('user', function ($q) use ($query) {
                $q->where('full_name', 'LIKE', "%{$query}%")->where('otp_verified', true)->where('status', 'active');
            })
            ->select('id', 'user_id', 'title')
            ->get()
            ->map(function ($psychologist) {
                return [
                    'id' => $psychologist->id,
                    'full_name' => $psychologist->user->full_name,
                    'title' => $psychologist->title,
                    'photo_url' => $psychologist->user->photo_url,
                    'gender' => $psychologist->user->gender,
                ];
            });

        return response()->json($results);
    }

    public function showReview($id) {
        $psychologist = Psychologist::with('user', 'reviews')->whereHas('user', function($query) {
            $query->where('otp_verified', true)
                ->where('status', 'active');
            })
            ->findOrFail($id);
        
        return view('patient.psychologist.review', compact('psychologist'));
    }

    public function showClients()
    {
        $psychologist = Auth::user()->psychologist;

        $clients = User::whereIn('id', function($query) use ($psychologist) {
            $query->select('user_id') ->from('appointments')->where('psychologist_id', $psychologist->id)->whereIn('status', ['confirmed', 'completed', 'pending_payment', 'pending']);
        })->with(['appointments' => function($q) use ($psychologist) {
            $q->where('psychologist_id', $psychologist->id)
            ->whereIn('status', ['confirmed', 'completed', 'pending_payment', 'pending'])
            ->orderBy('date', 'desc');
        }])->get();

        return view('psychologist.clients.index', compact('clients'));
    }

     public function showClientDetails($clientId)
    {
        $psychologist = Auth::user()->psychologist;
        $client = User::findOrFail($clientId);
        $hasAppointments = $client->appointments()
            ->where('psychologist_id', $psychologist->id)
            ->exists();

        if (!$hasAppointments) {
            abort(403, 'You do not have access to this client');
        }

        $appointments = $client->appointments()
            ->where('psychologist_id', $psychologist->id)
            ->whereIn('status', ['confirmed', 'completed', 'pending_payment', 'pending'])
            ->orderBy('date', 'desc')
            ->get();

        return view('psychologist.clients.details', compact('client', 'appointments'));
    }

    public function getClientAppointments(User $client)
    {
        $psychologist = Auth::user()->psychologist;

        $appointments = $client->appointments()
            ->where('psychologist_id', $psychologist->id)
            ->whereIn('status', ['confirmed', 'completed'])
            ->orderBy('date', 'desc')
            ->get(['id', 'date', 'start_time', 'status', 'notes']);

        return response()->json($appointments);
    }

    public function storeSessionNotes(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'notes' => 'required|string|max:2000',
        ]);

        try {
            $appointment = Appointment::findOrFail($request->appointment_id);
            $psychologist = Auth::user()->psychologist;

            if (!$psychologist || $appointment->psychologist_id !== $psychologist->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to appointment. Psychologist ID mismatch.',
                ], 403);
            }

            $appointment->update(['notes' => $request->notes]);
            return response()->json([
                'success' => true,
                'message' => 'Session notes saved successfully!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save notes: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getGeneralNotes($clientId)
    {
        $notes = PsychologistClientNote::where([
            'psychologist_id' => auth()->id(),
            'client_id' => $clientId,
        ])->first();

        return response()->json([
            'general_notes' => $notes ? $notes->general_notes : '',
        ]);
    }

    public function storeGeneralNotes(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:users,id',
            'general_notes' => 'required|string|max:2000',
        ]);

        try {
            $generalNotes = PsychologistClientNote::updateOrCreate(
                [
                    'psychologist_id' => auth()->id(),
                    'client_id' => $request->client_id,
                ],
                [
                    'general_notes' => $request->general_notes,
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'General notes saved successfully!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save notes: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function showSchedule()
    {
        $psychologist = Auth::user()->psychologist;

        Appointment::where('psychologist_id', $psychologist->id)
            ->where('status', 'confirmed')
            ->whereRaw("CONCAT(date, ' ', end_time) < ?", [now()->format('Y-m-d H:i:s')])
            ->update(['status' => 'completed']);

        Appointment::where('psychologist_id', $psychologist->id)
            ->where('status', 'pending_payment')
            ->whereRaw("CONCAT(date, ' ', end_time) < ?", [now()->format('Y-m-d H:i:s')])
            ->update(['status' => 'cancelled']);

        $upcomingAppointments = $psychologist->appointments()
            ->with('user')
            ->whereIn('status', ['confirmed', 'pending_payment', 'scheduled'])
            ->whereNull('reschedule_date')
            ->whereRaw("CONCAT(date, ' ', end_time) > ?", [now()->format('Y-m-d H:i:s')])
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        $historyAppointments = $psychologist->appointments()
            ->with('user')
            ->whereIn('status', ['completed', 'cancelled'])
            ->whereNull('reschedule_date')
            ->orderBy('date', 'desc')
            ->get();

        $rescheduleAppointments = $psychologist->appointments()
            ->with('user')
            ->whereNotNull('reschedule_date')
            ->where('status', '!=', 'cancelled')
            ->orderBy('reschedule_date')
            ->orderBy('reschedule_time')
            ->get();

        return view('psychologist.appointment.index', compact(
            'upcomingAppointments',
            'historyAppointments',
            'rescheduleAppointments'
        ));
    }
}
