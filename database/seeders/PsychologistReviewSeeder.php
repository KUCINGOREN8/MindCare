<?php

namespace Database\Seeders;

use App\Models\Psychologist;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PsychologistReview;
use App\Models\User;

class PsychologistReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $psychologist = Psychologist::whereHas('user', function($q) {
            $q->where('email', 'doctor@gmail.com');
        })->first();

        $patient1 = User::where('email', 'test@gmail.com')->first();


        $reviews = [
            [
                'psychologist_id' => $psychologist->id,
                'user_id' => $patient1->id,
                'rating' => 5,
                'review' => 'Amazing psychologist, very helpful and professional.',
            ],
            [
                'psychologist_id' => $psychologist->id,
                'user_id' => $patient1->id,
                'rating' => 5,
                'review' => 'Very patient, warm, and understanding. I always feel safe during sessions.',
            ],
            [
                'psychologist_id' => $psychologist->id,
                'user_id' => $patient1->id,
                'rating' => 4,
                'review' => 'Very professional and supportive. Gave practical tools I could apply daily.',
            ],
        ];

        foreach ($reviews as $r) {
            PsychologistReview::create($r);
        }
    }
}
