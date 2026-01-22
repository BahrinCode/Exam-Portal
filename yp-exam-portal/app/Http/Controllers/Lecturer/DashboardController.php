<?php
// app/Http/Controllers/Lecturer/DashboardController.php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ClassModel;
use App\Models\Subject;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $stats = [
            'total_classes' => $user->classesAsLecturer()->count(),
            'total_subjects' => $user->subjectsAsLecturer()->count(),
            'total_exams' => $user->examsAsLecturer()->count(),
            'active_exams' => $user->examsAsLecturer()->where('is_published', true)->count(),
        ];

        $recentExams = $user->examsAsLecturer()
            ->with(['subject', 'class'])
            ->latest()
            ->take(5)
            ->get();

        return view('lecturer.dashboard', compact('stats', 'recentExams'));
    }
}