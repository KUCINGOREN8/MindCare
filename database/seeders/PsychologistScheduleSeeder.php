<?php

namespace Database\Seeders;

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
        $schedules = [
            [
                'psychologist_id' => '1',
                'day_of_week' => 'mon',
                'start_time' => '09:00',
                'end_time' => '15:00',
            ],
            [
                'psychologist_id' => '1',
                'day_of_week' => 'tue',
                'start_time' => '10:00',
                'end_time' => '14:00',
            ],
            [
                'psychologist_id' => '1',
                'day_of_week' => 'thu',
                'start_time' => '09:00',
                'end_time' => '17:00',
            ],
            [
                'psychologist_id' => '1',
                'day_of_week' => 'sat',
                'start_time' => '10:00',
                'end_time' => '13:00',
            ],
        ];

        foreach ($schedules as $s) {
            PsychologistSchedule::create($s);
        }
    }
}
