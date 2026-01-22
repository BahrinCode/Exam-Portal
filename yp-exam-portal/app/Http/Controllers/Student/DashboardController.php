<?php
// app/Http/Controllers/Student/DashboardController.php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamAttempt;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Get classes the student is enrolled in
        $classIds = $user->studentClasses()->pluck('classes.id');
        
        // Get available exams for those classes
        $availableExams = Exam::whereIn('class_id', $classIds)
            ->where('is_published', true)
            ->where(function ($query) {
                $query->whereNull('start_time')
                    ->orWhere('start_time', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('end_time')
                    ->orWhere('end_time', '>=', now());
            })
            ->whereDoesntHave('attempts', function ($query) use ($user) {
                $query->where('student_id', $user->id)
                    ->whereIn('status', ['completed', 'in_progress']);
            })
            ->with(['subject', 'class'])
            ->get();

        $ongoingAttempt = ExamAttempt::where('student_id', $user->id)
            ->where('status', 'in_progress')
            ->with('exam')
            ->first();

        $pastExams = ExamAttempt::where('student_id', $user->id)
            ->where('status', 'completed')
            ->with('exam')
            ->latest()
            ->take(5)
            ->get();

        return view('student.dashboard', compact('availableExams', 'ongoingAttempt', 'pastExams'));
    }
}