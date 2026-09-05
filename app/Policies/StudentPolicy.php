<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;

class StudentPolicy
{
    public function view(User $user, Student $student): bool
    {
        return $user->halaqas()
            ->whereKey($student->halaqa_id)
            ->exists();
    }

    public function update(User $user, Student $student): bool
    {
        return $user->halaqas()
            ->whereKey($student->halaqa_id)
            ->exists();
    }

    public function delete(User $user, Student $student): bool
    {
        return $user->halaqas()
            ->whereKey($student->halaqa_id)
            ->exists();
    }

    private function ownsStudent(User $user, Student $student): bool
    {
        $userHalaqa = $user->halaqas()->first();

        return $userHalaqa && $student->halaqa_id === $userHalaqa->id;
    }
}
