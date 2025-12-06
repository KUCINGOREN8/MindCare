<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Psychologist;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;


class BookAppointmentController extends Controller
{
    public function showBook()
    {

        $psychologists = Psychologist::with('user')->get();

        return view('pages.appointment.book-appointment', compact('psychologists'));

    }



    public function showAvailableTimes(Request $request)
    {
        $psychologistId = $request->input('psychologist_id');
        $date = $request->input('date');



        $availableTimes = [
            '09:00 AM', '09:30 AM', '10:00 AM', '10:30 AM',
            '11:00 AM', '01:00 PM', '02:00 PM', '03:00 PM'
        ];

        return response()->json($availableTimes);
    }

    public function store(Request $request)
    {
        $request->validate([
            'psychologist_id' => 'required|exists:psychologists,id',
            'date' => 'required|date',
            'time' => 'required'
        ]);

        $psychologist = Psychologist::findOrFail($request->psychologist_id);

        Appointment::create([
            'user_id' => Auth::id(),
            'psychologist_id' => $psychologist->id,
            'with' => $psychologist->user->full_name,
            'job_title' => $psychologist->title,
            'date' => $request->date,
            'time' => $request->time,
            'status' => 'pending',
        ]);

        // return redirect()->route('appointments.history')
        //                  ->with('success', 'Your appointment has been booked!');
    }





}
