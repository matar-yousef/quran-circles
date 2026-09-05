<?php

namespace App\Policies;

use App\Models\DailyTracking;
use App\Models\User;

class DailyTrackingPolicy
{
    public function manage(User $user, DailyTracking $dailyTracking): bool
    {
        return $dailyTracking->student->halaqa->users()->where('users.id', $user->id)->exists();
    }
}
