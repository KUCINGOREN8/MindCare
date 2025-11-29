<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PsychologistEducation;

class PsychologistEducationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $educations = [
            [
                'psychologist_id' => '1',
                'degree' => 'Master of Clinical Psychology',
                'institution' => 'University of Melbourne',
                'year' => '2012',
            ],
            [
                'psychologist_id' => '1',
                'degree' => 'Master of Science in Psychology',
                'institution' => 'University of Melbourne',
                'year' => '2015',
            ],
        ];

        foreach ($educations as $e) {
            PsychologistEducation::create($e);
        }
    }
}
