<?php

use App\Http\Controllers\Lecturer\QuestionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Lecturer\DashboardController as LecturerDashboardController;
use App\Http\Controllers\Lecturer\ClassController;
use App\Http\Controllers\Lecturer\SubjectController;
use App\Http\Controllers\Lecturer\ExamController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\ExamController as StudentExamController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->isLecturer()) {
            return redirect()->route('lecturer.dashboard');
        } elseif (auth()->user()->isStudent()) {
            return redirect()->route('student.dashboard');
        }
        return view('dashboard');
    })->name('dashboard');

    // Profile routes (from Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Lecturer Routes
    Route::middleware(['lecturer'])->prefix('lecturer')->name('lecturer.')->group(function () {
        Route::get('/dashboard', [LecturerDashboardController::class, 'index'])->name('dashboard');
        
        // Classes
        Route::resource('classes', ClassController::class);
        Route::post('/classes/{class}/add-student', [ClassController::class, 'addStudent'])->name('classes.add-student');
        Route::delete('/classes/{class}/remove-student/{student}', [ClassController::class, 'removeStudent'])->name('classes.remove-student');
        
        // Subjects
        Route::resource('subjects', SubjectController::class);
        Route::post('/subjects/{subject}/assign-class', [SubjectController::class, 'assignClass'])->name('subjects.assign-class');
        Route::delete('/subjects/{subject}/remove-class/{class}', [SubjectController::class, 'removeClass'])->name('subjects.remove-class');
        
        // Exams
        Route::resource('exams', ExamController::class);
        Route::post('/exams/{exam}/publish', [ExamController::class, 'publish'])->name('exams.publish');
        Route::post('/exams/{exam}/unpublish', [ExamController::class, 'unpublish'])->name('exams.unpublish');
        
        // Questions (nested under exams)
        Route::resource('exams.questions', QuestionController::class);
        
        // This will create routes like:
        // - lecturer.exams.questions.index
        // - lecturer.exams.questions.create
        // - lecturer.exams.questions.store
        // - lecturer.exams.questions.show
        // - lecturer.exams.questions.edit
        // - lecturer.exams.questions.update
        // - lecturer.exams.questions.destroy
    });

    // Student Routes
    Route::middleware(['student'])->prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
        
        // Exams
        Route::get('/exams/{exam}', [StudentExamController::class, 'show'])->name('exams.show');
        Route::post('/exams/{exam}/start', [StudentExamController::class, 'start'])->name('exams.start');
        Route::get('/exams/{exam}/take', [StudentExamController::class, 'take'])->name('exams.take');
        Route::post('/exams/{exam}/questions/{question}/answer', [StudentExamController::class, 'submitAnswer'])->name('exams.submit-answer');
        Route::post('/exams/{exam}/submit', [StudentExamController::class, 'submitExam'])->name('exams.submit');
        Route::get('/exams/attempt/{attempt}/results', [StudentExamController::class, 'results'])->name('exams.results');
    });
});

Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');

// Include Breeze authentication routes
require __DIR__.'/auth.php';