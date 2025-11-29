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
            [
                'psychologist_id' => '2',
                'user_id' => '2',
                'rating' => 5,
                'review' => 'Highly insightful and empathetic. Helped me understand my anxiety triggers clearly.',
            ],
            [
                'psychologist_id' => '2',
                'user_id' => '3',
                'rating' => 4,
                'review' => 'Very professional and supportive. Gave practical tools I could apply daily.',
            ],
            [
                'psychologist_id' => '2',
                'user_id' => '4',
                'rating' => 5,
                'review' => 'She helped me regain confidence and manage stress at work. Truly life-changing sessions.',
            ],
            [
                'psychologist_id' => '2',
                'user_id' => '5',
                'rating' => 4,
                'review' => 'Warm and understanding, though sometimes sessions felt a bit rushed. Still very helpful.',
            ],
        ];

        foreach ($reviews as $r) {
            PsychologistReview::create($r);
        }
    }
}
