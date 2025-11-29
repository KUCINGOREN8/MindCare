<?php

namespace Database\Seeders;

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
        $experiences = [
            [
                'psychologist_id' => '1',
                'position' => 'Clinical Psychologist',
                'organization' => 'Serenity Wellness Clinic',
                'start_year' => '2014',
                'end_year' => '2017',
            ],
            [
                'psychologist_id' => '1',
                'position' => 'Volunteer Counselor',
                'organization' => 'Youth Support Center',
                'start_year' => '2017',
                'end_year' => '2020',
            ],
            [
                'psychologist_id' => '1',
                'position' => 'Clinical Psychologist',
                'organization' => 'Serenity Wellness Clinic',
                'start_year' => '2020',
            ],
        ];

        foreach ($experiences as $e) {
            PsychologistExperience::create($e);
        }
    }
}
