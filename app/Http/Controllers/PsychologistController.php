<?php

namespace App\Http\Controllers;

use App\Models\Psychologist;
use App\Http\Requests\StorePsychologistRequest;
use App\Http\Requests\UpdatePsychologistRequest;

class PsychologistController extends Controller
{
    
    public function showFindPsychologist()
    {
        $psychologists = Psychologist::all();

        return view('pages.psychologist.find', compact('psychologists'));
    }

    public function showProfile($id) {
        $psychologist = Psychologist::with([
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

    public function showReview($id) {
        $psychologist = Psychologist::with('reviews')->orderBy('created_at', 'desc')->findOrFail($id);

        return view('pages.psychologist.review', compact('psychologist'));
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
