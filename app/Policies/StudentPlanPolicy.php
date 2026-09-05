<?php

namespace App\Policies;

use App\Models\StudentPlan;
use App\Models\User;

class StudentPlanPolicy
{
    public function manage(User $user, StudentPlan $studentPlan): bool
    {
        return $studentPlan->student->halaqa->users()->where('users.id', $user->id)->exists();
    }
}
