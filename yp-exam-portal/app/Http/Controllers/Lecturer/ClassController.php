<?php
// app/Http/Controllers/Lecturer/ClassController.php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller; // Make sure to extend Controller
use App\Models\ClassModel;
use App\Models\User;
use Illuminate\Http\Request;

class ClassController extends Controller // Make sure it extends Controller
{
    public function index()
    {
        $classes = auth()->user()->classesAsLecturer()->withCount('students')->get();
        return view('lecturer.classes.index', compact('classes'));
    }

    public function create()
    {
        return view('lecturer.classes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        ClassModel::create([
            'name' => $request->name,
            'description' => $request->description,
            'lecturer_id' => auth()->id(),
        ]);

        return redirect()->route('lecturer.classes.index')
            ->with('success', 'Class created successfully.');
    }

    public function show(ClassModel $class)
    {
        // Check if the class belongs to the lecturer
        if ($class->lecturer_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $students = $class->students()->get();
        $availableStudents = User::where('role', 'student')
            ->whereDoesntHave('studentClasses', function ($query) use ($class) {
                $query->where('class_id', $class->id);
            })
            ->get();

        return view('lecturer.classes.show', compact('class', 'students', 'availableStudents'));
    }

    public function edit(ClassModel $class)
    {
        // Check if the class belongs to the lecturer
        if ($class->lecturer_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('lecturer.classes.edit', compact('class'));
    }

    public function update(Request $request, ClassModel $class)
    {
        // Check if the class belongs to the lecturer
        if ($class->lecturer_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $class->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('lecturer.classes.show', $class)
            ->with('success', 'Class updated successfully.');
    }

    public function destroy(ClassModel $class)
    {
        // Check if the class belongs to the lecturer
        if ($class->lecturer_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $class->delete();

        return redirect()->route('lecturer.classes.index')
            ->with('success', 'Class deleted successfully.');
    }

    public function addStudent(Request $request, ClassModel $class)
    {
        // Check if the class belongs to the lecturer
        if ($class->lecturer_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'student_id' => 'required|exists:users,id',
        ]);

        $class->students()->attach($request->student_id);

        return back()->with('success', 'Student added to class.');
    }

    public function removeStudent(ClassModel $class, User $student)
    {
        // Check if the class belongs to the lecturer
        if ($class->lecturer_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $class->students()->detach($student->id);

        return back()->with('success', 'Student removed from class.');
    }
}