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
            [
                'psychologist_id' => '2',
                'degree' => 'Bachelor of Psychology',
                'institution' => 'Universitas Indonesia',
                'year' => '2010',
            ],
            [
                'psychologist_id' => '2',
                'degree' => 'Master of Counseling Psychology',
                'institution' => 'Monash University',
                'year' => '2013',
            ],
            [
                'psychologist_id' => '2',
                'degree' => 'Professional Certification in Cognitive Behavioral Therapy (CBT)',
                'institution' => 'Beck Institute, USA',
                'year' => '2016',
            ],
        ];

        foreach ($educations as $e) {
            PsychologistEducation::create($e);
        }
    }
}
