<?php
// database/seeders/RoleSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Create lecturer
        User::create([
            'name' => 'John Lecturer',
            'email' => 'lecturer@example.com',
            'password' => bcrypt('password'),
            'role' => 'lecturer',
        ]);

        // Create students
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'name' => "Student $i",
                'email' => "student$i@example.com",
                'password' => bcrypt('password'),
                'role' => 'student',
                'student_id' => 'STU' . str_pad($i, 3, '0', STR_PAD_LEFT),
            ]);
        }
    }
}