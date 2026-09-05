<?php

namespace App\Policies;

use App\Models\StudentPlan;
use App\Models\User;

class StudentPlanPolicy
{
    /**
     * التحقق مما إذا كانت خطة الطالب تتبع لطالب مسجل في إحدى حلقات المستخدم
     */
    public function manage(User $user, StudentPlan $studentPlan): bool
    {
        return $studentPlan->student->halaqa->users()->where('users.id', $user->id)->exists();
    }
}
