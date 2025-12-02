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
        $schedules = [
            [
                'psychologist_id' => '1',
        $psychologist = Psychologist::whereHas('user', function($q) {
            $q->where('email', 'doctor@gmail.com');
        })->first();

        $schedules = [
            [
                'psychologist_id' => $psychologist->id,
                'day_of_week' => 'mon',
                'start_time' => '09:00',
                'end_time' => '15:00',
            ],
            [
<<<<<<< HEAD
                'psychologist_id' => '1',
=======
                'psychologist_id' => $psychologist->id,
>>>>>>> 05965638d654be5556a9a63ac9d22ecc8010904b
                'day_of_week' => 'tue',
                'start_time' => '10:00',
                'end_time' => '14:00',
            ],
            [
<<<<<<< HEAD
                'psychologist_id' => '1',
=======
                'psychologist_id' => $psychologist->id,
>>>>>>> 05965638d654be5556a9a63ac9d22ecc8010904b
                'day_of_week' => 'thu',
                'start_time' => '09:00',
                'end_time' => '17:00',
            ],
            [
<<<<<<< HEAD
                'psychologist_id' => '1',
=======
                'psychologist_id' => $psychologist->id,
>>>>>>> 05965638d654be5556a9a63ac9d22ecc8010904b
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
