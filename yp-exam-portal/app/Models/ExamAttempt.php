<?php
// app/Models/ExamAttempt.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id', 'student_id', 'start_time', 'end_time',
        'total_marks_obtained', 'status'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime'
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function calculateRemainingTime()
    {
        if (!$this->start_time || $this->status !== 'in_progress') {
            return 0;
        }

        $examDuration = $this->exam->duration_minutes * 60;
        $elapsedTime = now()->diffInSeconds($this->start_time);
        $remainingTime = max(0, $examDuration - $elapsedTime);

        return $remainingTime;
    }
}