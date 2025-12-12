<?php

namespace App\Http\Controllers;

use App\Models\Psychologist;
use App\Http\Requests\StorePsychologistRequest;
use App\Http\Requests\UpdatePsychologistRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PsychologistController extends Controller
{

    public function showFindPsychologist()
    {
        $psychologists = Psychologist::with('user')->get();

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
            ->findOrFail($id);

        return view('patient.psychologist.profile', compact('psychologist'));
    }

    public function showSearch(Request $request) {

        $query = $request->input('q');

         $results = Psychologist::with('user')
            ->whereHas('user', function ($q) use ($query) {
                $q->where('full_name', 'LIKE', "%{$query}%");
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
        $psychologist = Psychologist::with('user', 'reviews')->orderBy('created_at', 'desc')->findOrFail($id);

        return view('patient.psychologist.review', compact('psychologist'));
    }

    public function showClients() {
        $psychologist = Auth::user()->psychologist;
        $clients = $psychologist->reviews()->with('user')->get()->pluck('user')->unique('user_id')->values();

        return view('psychologist.clients.index', compact('clients'));
    }

}
