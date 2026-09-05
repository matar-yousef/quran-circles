<?php

namespace App\Policies;

use App\Models\Exam;
use App\Models\User;

class ExamPolicy
{
    public function manage(User $user, Exam $exam): bool
    {
        return $exam->student->halaqa->users()->where('users.id', $user->id)->exists();
    }
}
