<?php
// database/seeders/TestDataSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ClassModel;
use App\Models\Subject;
use App\Models\Exam;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // Get lecturer
        $lecturer = User::where('email', 'lecturer@example.com')->first();
        
        // Get a student
        $student = User::where('email', 'student1@example.com')->first();
        
        if ($lecturer && $student) {
            // Create a class
            $class = ClassModel::create([
                'name' => 'Computer Science 101',
                'description' => 'Introduction to Computer Science',
                'lecturer_id' => $lecturer->id,
            ]);
            
            // Add student to class
            $class->students()->attach($student->id);
            
            // Create a subject
            $subject = Subject::create([
                'name' => 'Programming Fundamentals',
                'description' => 'Basic programming concepts',
                'lecturer_id' => $lecturer->id,
            ]);
            
            // Associate subject with class
            $subject->classes()->attach($class->id);
            
            // Create an exam
            $exam = Exam::create([
                'title' => 'Midterm Exam',
                'description' => 'Covers chapters 1-5',
                'subject_id' => $subject->id,
                'class_id' => $class->id,
                'lecturer_id' => $lecturer->id,
                'duration_minutes' => 60,
                'total_marks' => 100,
                'passing_marks' => 50,
                'is_published' => true,
            ]);
        }
    }
}