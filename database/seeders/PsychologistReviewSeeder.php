<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PsychologistReview;

class PsychologistReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reviews = [
            [
                'psychologist_id' => '1',
                'user_id' => '1',
                'rating' => 5,
                'review' => 'Amazing psychologist, very helpful and professional.',
            ],
            [
                'psychologist_id' => '1',
                'user_id' => '1',
                'rating' => 5,
                'review' => 'Very patient, warm, and understanding. I always feel safe during sessions.',
            ],
        ];

        foreach ($reviews as $r) {
            PsychologistReview::create($r);
        }
    }
}
