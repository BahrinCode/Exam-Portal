<?php
// app/Policies/ExamAttemptPolicy.php
namespace App\Policies;

use App\Models\ExamAttempt;
use App\Models\User;

class ExamAttemptPolicy
{
    public function view(User $user, ExamAttempt $attempt)
    {
        return $user->id === $attempt->student_id;
    }
    
    public function update(User $user, ExamAttempt $attempt)
    {
        return $user->id === $attempt->student_id;
    }
    
    public function delete(User $user, ExamAttempt $attempt)
    {
        return $user->id === $attempt->student_id;
    }
}