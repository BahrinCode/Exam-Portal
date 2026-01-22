<?php
// app/Http/Controllers/Student/ExamController.php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ExamController extends Controller
{
    public function show(Exam $exam)
    {
        $user = auth()->user();
        
        // Check if student is in the exam's class
        if (!$exam->class->students->contains($user)) {
            abort(403, 'You are not enrolled in this class.');
        }

        // Check if exam is available
        if (!$exam->isAvailable()) {
            abort(403, 'This exam is not currently available.');
        }

        // Check for existing attempt
        $attempt = ExamAttempt::where('exam_id', $exam->id)
            ->where('student_id', $user->id)
            ->first();

        if ($attempt) {
            if ($attempt->status === 'completed') {
                return redirect()->route('student.exams.results', $attempt);
            } elseif ($attempt->status === 'in_progress') {
                return redirect()->route('student.exams.take', $exam);
            }
        }

        return view('student.exams.show', compact('exam'));
    }

    public function start(Exam $exam)
    {
        $user = auth()->user();
        
        // Check if student is in the exam's class
        if (!$exam->class->students->contains($user)) {
            abort(403);
        }

        // Check if exam is available
        if (!$exam->isAvailable()) {
            abort(403);
        }

        // Create new attempt
        $attempt = ExamAttempt::create([
            'exam_id' => $exam->id,
            'student_id' => $user->id,
            'start_time' => now(),
            'status' => 'in_progress',
        ]);

        return redirect()->route('student.exams.take', $exam);
    }

    public function take(Exam $exam)
    {
        $user = auth()->user();
        
        $attempt = ExamAttempt::where('exam_id', $exam->id)
            ->where('student_id', $user->id)
            ->where('status', 'in_progress')
            ->firstOrFail();

        $remainingTime = $attempt->calculateRemainingTime();
        
        if ($remainingTime <= 0) {
            $attempt->update([
                'end_time' => now(),
                'status' => 'expired',
            ]);
            return redirect()->route('student.dashboard')->with('error', 'Exam time has expired.');
        }

        $questions = $exam->questions()->with(['answers' => function ($query) use ($attempt) {
            $query->where('exam_attempt_id', $attempt->id);
        }])->get();

        return view('student.exams.take', compact('exam', 'attempt', 'questions', 'remainingTime'));
    }

    // In app/Http/Controllers/Student/ExamController.php

    public function submitAnswer(Request $request, Exam $exam, Question $question)
    {
        $user = auth()->user();
        
        $attempt = ExamAttempt::where('exam_id', $exam->id)
            ->where('student_id', $user->id)
            ->where('status', 'in_progress')
            ->firstOrFail();

        // Decode options if needed
        $options = $question->options;
        if (is_string($options)) {
            $options = json_decode($options, true) ?? [];
        }
        
        // Prepare validation rule
        $validationRule = ['answer' => 'required|string'];
        
        if ($question->type === 'multiple_choice') {
            // Only validate against options if they exist
            if (!empty($options)) {
                $validationRule['answer'] = 'required|string|in:' . implode(',', $options);
            }
        }

        $request->validate($validationRule);

        $marksObtained = 0;
        $isCorrect = false;

        if ($question->type === 'multiple_choice') {
            // THIS IS THE KEY PART - Compare student's answer with correct answer
            $isCorrect = $request->answer === $question->correct_answer;
            $marksObtained = $isCorrect ? $question->marks : 0;
        }
        // Note: For open_text questions, marks are typically 0 unless manually graded

        Answer::updateOrCreate(
            [
                'exam_attempt_id' => $attempt->id,
                'question_id' => $question->id,
            ],
            [
                'answer' => $request->answer,
                'marks_obtained' => $marksObtained,
                'is_correct' => $isCorrect,
            ]
        );

        return response()->json(['success' => true]);
    }
   public function results(ExamAttempt $attempt)
    {
        // Replace authorize() with manual check
        if ($attempt->student_id !== auth()->id()) {
            abort(403, 'You are not authorized to view these results.');
        }
        
        $attempt->load(['exam.questions', 'answers.question']);
        
        $scorePercentage = ($attempt->total_marks_obtained / $attempt->exam->total_marks) * 100;
        $passed = $attempt->total_marks_obtained >= $attempt->exam->passing_marks;

        return view('student.exams.results', compact('attempt', 'scorePercentage', 'passed'));
    }
}   