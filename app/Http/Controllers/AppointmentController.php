<?php

namespace App\Http\Controllers;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    //
    
    public function index()
    {
        $ongoing = Appointment::whereIn('status', ['pending', 'confirmed'])
            ->orderBy('date')
            ->first();

        $history = Appointment::orderBy('date', 'desc')
            ->skip(0)
            ->take(5)
            ->get();

        return view('pages.appointment.appointments', compact('ongoing', 'history'));
    }

    public function loadMore(Request $request)
    {
        $offset = $request->offset;

        $items = Appointment::orderBy('date', 'desc')
            ->skip($offset)
            ->take(5)
            ->get();

        return response()->json($items);
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

    public function reschedule(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $appointment->reschedule_date = $request->reschedule_date;
        $appointment->reschedule_time = $request->reschedule_time;
        $appointment->reschedule_reason = $request->reschedule_reason;

        $appointment->save();

        return view('profile.appointments', compact('ongoing', 'history'));
    }
}

