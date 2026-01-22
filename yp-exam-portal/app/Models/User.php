<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'student_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isLecturer()
    {
        return $this->role === 'lecturer';
    }

    public function isStudent()
    {
        return $this->role === 'student';
    }

    public function classesAsLecturer()
    {
        return $this->hasMany(ClassModel::class, 'lecturer_id');
    }

    public function subjectsAsLecturer()
    {
        return $this->hasMany(Subject::class, 'lecturer_id');
    }

    public function examsAsLecturer()
    {
        return $this->hasMany(Exam::class, 'lecturer_id');
    }

    public function studentClasses()
    {
        return $this->belongsToMany(ClassModel::class, 'class_student', 'student_id', 'class_id')
                    ->withTimestamps();
    }

    public function examAttempts()
    {
        return $this->hasMany(ExamAttempt::class, 'student_id');
    }
}