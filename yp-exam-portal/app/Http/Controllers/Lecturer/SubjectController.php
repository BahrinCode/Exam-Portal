<?php
// app/Http/Controllers/Lecturer/SubjectController.php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\ClassModel;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = auth()->user()->subjectsAsLecturer()->withCount('classes')->get();
        return view('lecturer.subjects.index', compact('subjects'));
    }

    public function create()
    {
        $classes = auth()->user()->classesAsLecturer()->get();
        return view('lecturer.subjects.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'classes' => 'required|array',
            'classes.*' => 'exists:classes,id',
        ]);

        $subject = Subject::create([
            'name' => $request->name,
            'description' => $request->description,
            'lecturer_id' => auth()->id(),
        ]);

        $subject->classes()->attach($request->classes);

        return redirect()->route('lecturer.subjects.index')
            ->with('success', 'Subject created successfully.');
    }

    public function show(Subject $subject)
    {
        // Check if the subject belongs to the lecturer
        if ($subject->lecturer_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $classes = $subject->classes()->withCount('students')->get();
        $availableClasses = auth()->user()->classesAsLecturer()
            ->whereNotIn('id', $classes->pluck('id'))
            ->get();

        return view('lecturer.subjects.show', compact('subject', 'classes', 'availableClasses'));
    }

    public function edit(Subject $subject)
    {
        // Check if the subject belongs to the lecturer
        if ($subject->lecturer_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('lecturer.subjects.edit', compact('subject'));
    }

    public function update(Request $request, Subject $subject)
    {
        // Check if the subject belongs to the lecturer
        if ($subject->lecturer_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $subject->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('lecturer.subjects.show', $subject)
            ->with('success', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject)
    {
        // Check if the subject belongs to the lecturer
        if ($subject->lecturer_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $subject->delete();

        return redirect()->route('lecturer.subjects.index')
            ->with('success', 'Subject deleted successfully.');
    }

    public function assignClass(Request $request, Subject $subject)
    {
        // Check if the subject belongs to the lecturer
        if ($subject->lecturer_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'class_id' => 'required|exists:classes,id',
        ]);

        $subject->classes()->attach($request->class_id);

        return back()->with('success', 'Class assigned to subject.');
    }

    public function removeClass(Subject $subject, ClassModel $class)
    {
        // Check if the subject belongs to the lecturer
        if ($subject->lecturer_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $subject->classes()->detach($class->id);

        return back()->with('success', 'Class removed from subject.');
    }
}