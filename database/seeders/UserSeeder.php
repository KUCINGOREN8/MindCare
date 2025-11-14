<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'full_name' => 'Tester',
            'email' => 'test@gmail.com',
            'password' => Hash::make('123456'),
            'date_of_birth' => '2005-05-15',
            'gender' => 'female',
            'preferred_language' => 'id',
            'agree_to_terms' => true,
        ]);
    }
}
