<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    /**
     * Display a listing of questions for a specific exam.
     */
    public function index(Exam $exam)
    {
        $questions = $exam->questions()->paginate(10);
        
        return view('lecturer.questions.index', compact('exam', 'questions'));
    }

    /**
     * Show the form for creating a new question.
     */
    public function create(Exam $exam)
{
    // Debug: Check if we can reach this point
    \Log::info('QuestionController@create called for exam: ' . $exam->id);
    
    $questionTypes = [
        'multiple_choice' => 'Multiple Choice',
        'true_false' => 'True/False',
        'short_answer' => 'Short Answer',
        'essay' => 'Essay',
        'matching' => 'Matching',
    ];
    
    // Try using the full path
    $viewPath = 'lecturer.questions.create';
    
    // Check if view exists
    if (!view()->exists($viewPath)) {
        \Log::error("View not found: {$viewPath}");
        \Log::error("Looking in: " . resource_path('views/lecturer/questions/create.blade.php'));
        
        // Create a simple response for debugging
        return response("DEBUG: View '{$viewPath}' not found. Check logs.");
    }
    
    return view($viewPath, compact('exam', 'questionTypes'));
}

    /**
     * Store a newly created question in storage.
     */
public function store(Request $request, Exam $exam)
{
    $validated = $request->validate([
        'question_text' => 'required|string|max:2000',
        'type' => 'required|in:multiple_choice,open_text',
        'marks' => 'required|integer|min:1|max:100',
        'options' => 'nullable|array',
        'options.*' => 'nullable|string|max:500',
        'correct_option' => 'nullable|integer',
        'correct_answer' => 'nullable|string|max:1000',
    ]);

    $optionsData = null;
    $correctAnswer = null;

    if ($validated['type'] === 'multiple_choice') {
        // Filter out empty options
        $filteredOptions = array_filter($validated['options'] ?? []);
        
        if (count($filteredOptions) < 2) {
            return back()->withErrors(['options' => 'Multiple choice questions require at least 2 options.'])->withInput();
        }

        if (!isset($validated['correct_option'])) {
            return back()->withErrors(['correct_option' => 'Please select a correct option.'])->withInput();
        }

        $optionsData = $filteredOptions;
        
        // Get correct answer from selected option
        if (isset($filteredOptions[$validated['correct_option']])) {
            $correctAnswer = $filteredOptions[$validated['correct_option']];
        }
    } else {
        $correctAnswer = $validated['correct_answer'] ?? null;
    }

    // Create the question
    $question = $exam->questions()->create([
        'question_text' => $validated['question_text'],
        'type' => $validated['type'],
        'marks' => $validated['marks'],
        'options' => $optionsData ? json_encode($optionsData) : null,
        'correct_answer' => $correctAnswer,
    ]);

    return redirect()->route('lecturer.exams.questions.index', $exam)
        ->with('success', 'Question added successfully.');
}
    /**
     * Display the specified question.
     */
    public function show(Exam $exam, Question $question)
    {
        // Verify the question belongs to the exam
        if ($question->exam_id !== $exam->id) {
            abort(404);
        }
        
        return view('lecturer.questions.show', compact('exam', 'question'));
    }

    /**
     * Show the form for editing the specified question.
     */
    public function edit(Exam $exam, Question $question)
    {
        // Verify the question belongs to the exam
        if ($question->exam_id !== $exam->id) {
            abort(404);
        }
        
        $questionTypes = [
            'multiple_choice' => 'Multiple Choice',
            'true_false' => 'True/False',
            'short_answer' => 'Short Answer',
            'essay' => 'Essay',
            'matching' => 'Matching',
        ];
        
        // Decode options if they exist
        $options = [];
        $correctOptions = [];
        
        if ($question->options) {
            $decodedOptions = is_string($question->options) ? json_decode($question->options, true) : $question->options;
            
            if ($question->type === 'multiple_choice') {
                if (isset($decodedOptions['choices'])) {
                    $options = $decodedOptions['choices'];
                    $correctOptions = $decodedOptions['correct_indices'] ?? [];
                } elseif (is_array($decodedOptions)) {
                    $options = $decodedOptions;
                }
            } else {
                $options = is_array($decodedOptions) ? $decodedOptions : [];
            }
        }
        
        return view('lecturer.questions.edit', compact('exam', 'question', 'questionTypes', 'options', 'correctOptions'));
    }

    /**
     * Update the specified question in storage.
     */
    public function update(Request $request, Exam $exam, Question $question)
{
    // Verify the question belongs to the exam
    if ($question->exam_id !== $exam->id) {
        abort(404);
    }

    $validated = $request->validate([
        'question_text' => 'required|string|max:2000',
        'type' => 'required|in:multiple_choice,open_text',
        'marks' => 'required|integer|min:1|max:100',
        'options' => 'nullable|array',
        'options.*' => 'nullable|string|max:500',
        'correct_option' => 'nullable|integer',
        'correct_answer_final' => 'nullable|string|max:1000', // Make this nullable
    ]);

    $optionsData = null;
    $correctAnswer = $validated['correct_answer_final'] ?? null;

    if ($validated['type'] === 'multiple_choice') {
        // Filter out empty options
        $filteredOptions = array_filter($validated['options'] ?? []);
        
        if (count($filteredOptions) < 2) {
            return back()->withErrors(['options' => 'Multiple choice questions require at least 2 options.'])->withInput();
        }

        if (!isset($validated['correct_option'])) {
            return back()->withErrors(['correct_option' => 'Please select a correct option.'])->withInput();
        }

        $optionsData = $filteredOptions;
        
        // Make sure correct_answer_final is set from the selected option
        if (isset($filteredOptions[$validated['correct_option']])) {
            $correctAnswer = $filteredOptions[$validated['correct_option']];
        }
    }

    // Update the question
    $question->update([
        'question_text' => $validated['question_text'],
        'type' => $validated['type'],
        'marks' => $validated['marks'],
        'options' => $optionsData ? json_encode($optionsData) : null,
        'correct_answer' => $correctAnswer,
    ]);

    return redirect()->route('lecturer.exams.questions.index', $exam)
        ->with('success', 'Question updated successfully.');
}

    /**
     * Remove the specified question from storage.
     */
    public function destroy(Exam $exam, Question $question)
    {
        // Verify the question belongs to the exam
        if ($question->exam_id !== $exam->id) {
            abort(404);
        }
        
        $question->delete();
        
        return redirect()->route('lecturer.exams.questions.index', $exam)
            ->with('success', 'Question deleted successfully.');
    }

    /**
     * Toggle question status (if you have is_active field, otherwise remove this method).
     * Note: Your current model doesn't have is_active, but keeping it in case you add it later.
     */
    public function toggleStatus(Exam $exam, Question $question)
    {
        // Verify the question belongs to the exam
        if ($question->exam_id !== $exam->id) {
            abort(404);
        }
        
        // If you add is_active to your model later:
        // $question->update(['is_active' => !$question->is_active]);
        
        return back()->with('success', 'Question status updated.');
    }

    /**
     * Bulk delete questions.
     */
    public function bulkDestroy(Request $request, Exam $exam)
    {
        $request->validate([
            'question_ids' => 'required|array',
            'question_ids.*' => 'exists:questions,id,exam_id,' . $exam->id,
        ]);
        
        Question::where('exam_id', $exam->id)
            ->whereIn('id', $request->question_ids)
            ->delete();
        
        return back()->with('success', 'Selected questions deleted successfully.');
    }
}