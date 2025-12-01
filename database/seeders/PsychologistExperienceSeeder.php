<?php

namespace Database\Seeders;

use App\Models\Psychologist;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PsychologistExperience;

class PsychologistExperienceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $psychologist = Psychologist::whereHas('user', function($q) {
        $q->where('email', 'doctor@gmail.com');
        })->first();

        if ($psychologist) {
            $psychologist->experiences()->createMany([
                [
                    'position' => 'Clinical Psychologist',
                    'organization' => 'Serenity Wellness Clinic',
                    'start_year' => '2014',
                    'end_year' => '2017',
                ],
                [
                    'position' => 'Volunteer Counselor',
                    'organization' => 'Youth Support Center',
                    'start_year' => '2017',
                    'end_year' => '2020',
                ],
                [
                    'position' => 'Clinical Psychologist',
                    'organization' => 'Serenity Wellness Clinic',
                    'start_year' => '2020',
                    'end_year' => null,
                ],
            ]);
        }
    }
}
