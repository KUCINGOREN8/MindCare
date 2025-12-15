<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        User::create([
            'full_name' => 'Admin BeOkay',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456'),
            'date_of_birth' => '2005-01-01',
            'gender' => 'male',
            'preferred_language' => 'en',
            'agree_to_terms' => true,
            'role' => 'admin',
            'otp_verified' => true,
            'status' => 'active',
             
        ]);

            // Psychologist User
            User::create([
                'full_name' => 'Emily Chen',
                'email' => 'doctor@gmail.com',
                'password' => Hash::make('123456'),
                'date_of_birth' => '2005-01-01',
                'gender' => 'female',
                'preferred_language' => 'en',
                'agree_to_terms' => true,
                'role' => 'psychologist',
                'otp_verified' => true,
                'status' => 'active',
            ]);

        User::create([
            'full_name' => 'Tester Pasien',
            'email' => 'test@gmail.com',
            'password' => Hash::make('123456'),
            'date_of_birth' => '2005-05-15',
            'gender' => 'female',
            'preferred_language' => 'id',
            'agree_to_terms' => true,
            'role' => 'patient',
            'otp_verified' => true,
            'status' => 'active',
        ]);
    }
}
