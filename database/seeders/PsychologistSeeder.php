<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Psychologist;


class PsychologistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $psychologists = [
            [
                'full_name' => 'Jacob Jonas',
                'photo_url' => '',
                'gender' => 'male',
                'languages' => ['English', 'Indonesia'], 
                'short_bio' => 'Helps clients navigate stress, work-related burnout, and lifestyle challenges using a supportive and solution-focused approach.',
                'about_me' => 'I am a Clinical Psychologist with more than 12 years of experience helping clients navigate depression, anxiety, trauma, and emotional struggles. My approach focuses on building a safe, non-judgmental space where clients can explore their thoughts and feelings at their own pace. I combine evidence-based therapy with practical tools to help you create meaningful change in your everyday life.',
                'title' => 'Therapist',
                'specialization' => 'Anxiety, Stress Management, Burnout',
                'license_number' => 'PSY-221309',
                'years_experience' => '7',
                'consultation_fee' => '180000',
                'email' => 'jacobjones@gmail.com',
                'password' => Hash::make('123456'),
                'preferred_language' => 'en',
                'agree_to_terms' => true,
            ],
            [
                'full_name' => 'Emily Chen',
                'photo_url' => '',
                'gender' => 'female',
                'languages' => ['English', 'Indonesia'], 
                'short_bio' => 'Focuses on cognitive-behavioral therapy to help individuals manage anxiety, overthinking, and relationship challenges.',
                'about_me' => 'I am a Licensed Clinical Psychologist with 10+ years of experience working with young adults and professionals dealing with anxiety, overthinking, and relationship issues. My therapy approach is collaborative, evidence-based, and focused on helping clients build emotional resilience and healthier thinking patterns.',
                'title' => 'Clinical Psychologist',
                'specialization' => 'Anxiety, CBT, Relationship Therapy',
                'license_number' => 'PSY-198432',
                'years_experience' => '10',
                'consultation_fee' => '200000',
                'email' => 'michael.hartono@example.com',
                'password' => Hash::make('123456'),
                'preferred_language' => 'en',
                'agree_to_terms' => true,
            ],
        ];

        foreach ($psychologists as $p) {
            Psychologist::create($p);
        }
    }
}
