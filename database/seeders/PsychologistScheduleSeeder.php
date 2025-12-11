<?php

namespace Database\Seeders;

use App\Models\Psychologist;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PsychologistSchedule;

class PsychologistScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $psychologist = Psychologist::whereHas('user', function($q) {
            $q->where('email', 'doctor@gmail.com');
        })->first();

        $schedules = [
            [
                'psychologist_id' => $psychologist->id,
                'day_of_week' => 'monday',
                'start_time' => '09:00',
                'end_time' => '15:00',
            ],
            [
                'psychologist_id' => $psychologist->id,
                'day_of_week' => 'tuesday',
                'start_time' => '05:00',
                'end_time' => '24:00',
            ],
            [
                'psychologist_id' => $psychologist->id,
                'day_of_week' => 'wednesday',
                'start_time' => '22:00',
                'end_time' => '24:00',
            ],
            [
                'psychologist_id' => $psychologist->id,
                'day_of_week' => 'thursday',
                'start_time' => '09:00',
                'end_time' => '24:00',
            ],
            [
                'psychologist_id' => $psychologist->id,
                'day_of_week' => 'saturday',
                'start_time' => '10:00',
                'end_time' => '13:00',
            ],
        ];

        foreach ($schedules as $s) {
            PsychologistSchedule::create($s);
        }
    }
}
