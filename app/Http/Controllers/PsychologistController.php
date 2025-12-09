<?php

namespace App\Http\Controllers;

use App\Models\Psychologist;
use App\Http\Requests\StorePsychologistRequest;
use App\Http\Requests\UpdatePsychologistRequest;
use Illuminate\Http\Request;

class PsychologistController extends Controller
{

    public function showFindPsychologist()
    {
        $psychologists = Psychologist::with('user')->get();

        return view('pages.psychologist.find', compact('psychologists'));
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
                $query->latest()->take(2);
            }])
            ->findOrFail($id);

        return view('pages.psychologist.profile', compact('psychologist'));
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

        return view('pages.psychologist.review', compact('psychologist'));
    }

    public function showClient() {
        $psychologist = Psychologist::with('reviews')->get();
        $clients = $psychologist->reviews;

        return view('psychologist.clients.index', compact('clients'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePsychologistRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Psychologist $psychologist)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Psychologist $psychologist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePsychologistRequest $request, Psychologist $psychologist)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Psychologist $psychologist)
    {
        //
    }
}
