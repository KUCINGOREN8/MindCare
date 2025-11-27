<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Testimonial;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'John Doe',
                'position' => 'CEO of ABC',
                'message' => '"Be Okay helped me through my anxiety. The counselors are incredibly understanding and professional. I feel so much better now."',
            ],
            [
                'name' => 'Sarah Smith',
                'position' => 'Marketing Manager',
                'message' => '"The convenience of online sessions made it possible for me to get help. The platform is easy to use and very supportive."',
            ],
            [
                'name' => 'Michael Lee',
                'position' => 'Freelancer',
                'message' => '"I was hesitant at first, but Be Okay provided exactly what I needed. The counselors are caring and the process is seamless."',
            ],
            [
                'name' => 'Jessica Brown',
                'position' => 'Designer',
                'message' => '"Every session helps me understand myself better. I’m grateful for how supportive and encouraging the team has been."',
            ],
            [
                'name' => 'David Wilson',
                'position' => 'Entrepreneur',
                'message' => '"A wonderful experience from start to finish. I feel heard, supported, and so much stronger than before."',
            ],
            [
                'name' => 'Emily Davis',
                'position' => 'Student',
                'message' => '"The support team truly listens. I feel more confident and motivated after every session. It’s been life-changing."',
            ],
        ];

        Testimonial::insert($data);
    }
}
