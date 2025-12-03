<?php

namespace Database\Seeders;

use App\Models\Psychologist;
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
        $psychologist = Psychologist::whereHas('user', function($q) {
            $q->where('email', 'doctor@gmail.com');
        })->first();

        if ($psychologist) {
            $educations = [
                [
                    'psychologist_id' => $psychologist->id,
                    'degree' => 'Master of Clinical Psychology',
                    'institution' => 'University of Melbourne',
                    'year' => '2012',
                ],
                [
                    'psychologist_id' => $psychologist->id,
                    'degree' => 'Master of Science in Psychology',
                    'institution' => 'University of Melbourne',
                    'year' => '2015',
                ],
                [
                    'psychologist_id' => $psychologist->id,
                    'degree' => 'Bachelor of Psychology',
                    'institution' => 'Universitas Indonesia',
                    'year' => '2010',
                ],
            ];

            foreach ($educations as $e) {
                PsychologistEducation::create($e);
            }
        }
    }
}
