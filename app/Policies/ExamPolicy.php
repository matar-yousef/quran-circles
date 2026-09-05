<?php

namespace App\Policies;

use App\Models\Exam;
use App\Models\User;

class ExamPolicy
{
    /**
     * التحقق مما إذا كان الاختبار يتبع لطالب مسجل في إحدى حلقات المستخدم
     */
    public function manage(User $user, Exam $exam): bool
    {
        return $exam->student->halaqa->users()->where('users.id', $user->id)->exists();
    }
}
