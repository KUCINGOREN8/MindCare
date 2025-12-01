<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Psychologist;
use App\Models\User;

class PsychologistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userEmily = User::where('email', 'doctor@gmail.com')->first();

        $psychologists = [
            [
                'user_id' => $userEmily->id,
                'short_bio' => 'Focuses on cognitive-behavioral therapy to help individuals manage anxiety, overthinking, and relationship challenges.',
                'about_me' => 'I am a Licensed Clinical Psychologist with 10+ years of experience working with young adults and professionals dealing with anxiety, overthinking, and relationship issues. My therapy approach is collaborative, evidence-based, and focused on helping clients build emotional resilience and healthier thinking patterns.',
                'languages' => ['English', 'Indonesia'],
                'title' => 'Clinical Psychologist',
                'specialization' => 'Anxiety, CBT, Relationship Therapy',
                'license_number' => 'PSY-198432',
                'years_experience' => 10,
                'consultation_fee' => 200000.00,
            ],
        ];

        foreach ($psychologists as $p) {
            Psychologist::create($p);
        }
    }
}
