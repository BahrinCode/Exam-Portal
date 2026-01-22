<?php
// app/Models/Exam.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'subject_id', 'class_id', 'lecturer_id',
        'duration_minutes', 'total_marks', 'passing_marks',
        'start_time', 'end_time', 'is_published'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_published' => 'boolean'
    ];

    public function lecturer()
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    public function attempts()
    {
        return $this->hasMany(ExamAttempt::class);
    }

    public function isAvailable()
    {
        $now = now();
        return $this->is_published && 
               (!$this->start_time || $now >= $this->start_time) &&
               (!$this->end_time || $now <= $this->end_time);
    }
}