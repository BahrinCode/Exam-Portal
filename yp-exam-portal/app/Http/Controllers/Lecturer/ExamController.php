<?php
// app/Http/Controllers/Lecturer/ExamController.php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller; // Make sure this is imported
use App\Models\Exam;
use App\Models\Subject;
use App\Models\ClassModel;
use App\Models\Question;
use App\Models\ExamAttempt;
use Illuminate\Http\Request;

class ExamController extends Controller // Make sure it extends Controller
{
    public function index()
    {
        $exams = auth()->user()->examsAsLecturer()
            ->with(['subject', 'class'])
            ->latest()
            ->get();

        return view('lecturer.exams.index', compact('exams'));
    }

    public function create()
{
    $subjects = auth()->user()->subjectsAsLecturer()->get();
    $classes = auth()->user()->classesAsLecturer()->get();

    // Get subject_id from query parameter if provided
    $selectedSubjectId = request()->get('subject_id');
    
    return view('lecturer.exams.create', compact('subjects', 'classes', 'selectedSubjectId'));
}

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject_id' => 'required|exists:subjects,id',
            'class_id' => 'required|exists:classes,id',
            'duration_minutes' => 'required|integer|min:1',
            'total_marks' => 'required|integer|min:1',
            'passing_marks' => 'required|integer|min:0|lte:total_marks',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after:start_time',
        ]);

        $exam = Exam::create([
            'title' => $request->title,
            'description' => $request->description,
            'subject_id' => $request->subject_id,
            'class_id' => $request->class_id,
            'lecturer_id' => auth()->id(),
            'duration_minutes' => $request->duration_minutes,
            'total_marks' => $request->total_marks,
            'passing_marks' => $request->passing_marks,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return redirect()->route('lecturer.exams.questions.create', $exam)
            ->with('success', 'Exam created. Now add questions.');
    }

    public function show(Exam $exam)
    {
        // Check if the exam belongs to the lecturer
        if ($exam->lecturer_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $exam->load(['questions', 'attempts.student']);
        $questions = $exam->questions;
        
        $stats = [
            'total_attempts' => $exam->attempts()->count(),
            'completed_attempts' => $exam->attempts()->where('status', 'completed')->count(),
            'average_score' => $exam->attempts()->where('status', 'completed')->avg('total_marks_obtained') ?? 0,
        ];

        return view('lecturer.exams.show', compact('exam', 'questions', 'stats'));
    }

    public function edit(Exam $exam)
    {
        // Check if the exam belongs to the lecturer
        if ($exam->lecturer_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $subjects = auth()->user()->subjectsAsLecturer()->get();
        $classes = auth()->user()->classesAsLecturer()->get();

        return view('lecturer.exams.edit', compact('exam', 'subjects', 'classes'));
    }

    public function update(Request $request, Exam $exam)
    {
        // Check if the exam belongs to the lecturer
        if ($exam->lecturer_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject_id' => 'required|exists:subjects,id',
            'class_id' => 'required|exists:classes,id',
            'duration_minutes' => 'required|integer|min:1',
            'total_marks' => 'required|integer|min:1',
            'passing_marks' => 'required|integer|min:0|lte:total_marks',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after:start_time',
        ]);

        $exam->update([
            'title' => $request->title,
            'description' => $request->description,
            'subject_id' => $request->subject_id,
            'class_id' => $request->class_id,
            'duration_minutes' => $request->duration_minutes,
            'total_marks' => $request->total_marks,
            'passing_marks' => $request->passing_marks,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return redirect()->route('lecturer.exams.show', $exam)
            ->with('success', 'Exam updated successfully.');
    }

    public function destroy(Exam $exam)
    {
        // Check if the exam belongs to the lecturer
        if ($exam->lecturer_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $exam->delete();

        return redirect()->route('lecturer.exams.index')
            ->with('success', 'Exam deleted successfully.');
    }

    public function publish(Exam $exam)
    {
        // Check if the exam belongs to the lecturer
        if ($exam->lecturer_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $exam->update(['is_published' => true]);

        return back()->with('success', 'Exam published successfully.');
    }

    public function unpublish(Exam $exam)
    {
        // Check if the exam belongs to the lecturer
        if ($exam->lecturer_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $exam->update(['is_published' => false]);

        return back()->with('success', 'Exam unpublished successfully.');
    }

    public function createQuestions(Exam $exam)
    {
        // Check if the exam belongs to the lecturer
        if ($exam->lecturer_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('lecturer.exams.create-questions', compact('exam'));
    }

    public function storeQuestions(Request $request, Exam $exam)
    {
        // Check if the exam belongs to the lecturer
        if ($exam->lecturer_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string',
            'questions.*.type' => 'required|in:multiple_choice,open_text',
            'questions.*.marks' => 'required|integer|min:1',
            'questions.*.options' => 'required_if:questions.*.type,multiple_choice|array|min:2',
            'questions.*.correct_answer' => 'required_if:questions.*.type,multiple_choice|string',
        ]);

        foreach ($request->questions as $questionData) {
            $question = new Question([
                'question_text' => $questionData['question_text'],
                'type' => $questionData['type'],
                'marks' => $questionData['marks'],
                'options' => $questionData['type'] === 'multiple_choice' ? $questionData['options'] : null,
                'correct_answer' => $questionData['type'] === 'multiple_choice' ? $questionData['correct_answer'] : null,
            ]);

            $exam->questions()->save($question);
        }

        return redirect()->route('lecturer.exams.show', $exam)
            ->with('success', 'Questions added successfully.');
    }
    public function showQuestions(Exam $exam)
{
    // Check if the exam belongs to the lecturer
    if ($exam->lecturer_id !== auth()->id()) {
        abort(403, 'Unauthorized action.');
    }

    $questions = $exam->questions()->get();
    return view('lecturer.exams.questions', compact('exam', 'questions'));
}

    public function destroyQuestion(Question $question)
    {
        $exam = $question->exam;

        // Check if the exam belongs to the lecturer
        if ($exam->lecturer_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $question->delete();

        return back()->with('success', 'Question deleted successfully.');
    }
}