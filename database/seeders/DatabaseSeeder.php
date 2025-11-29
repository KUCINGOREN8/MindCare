<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
            PsychologistSeeder::class,
            PsychologistEducationSeeder::class,
            PsychologistExperienceSeeder::class,
            PsychologistScheduleSeeder::class,
            PsychologistReviewSeeder::class,
        ]);
        $this->call(TestimonialSeeder::class);
    }
}

