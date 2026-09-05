<?php

namespace App\Policies;

use App\Models\Halaqa;
use App\Models\User;

class HalaqaPolicy
{
    /**
     * Determine whether the user can view the halaqa.
     */
    public function view(User $user, Halaqa $halaqa): bool
    {
        return $halaqa->users()->where('users.id', $user->id)->exists();
    }

    /**
     * Determine whether the user can update the halaqa.
     */
    public function update(User $user, Halaqa $halaqa): bool
    {
        return $this->view($user, $halaqa);
    }

    /**
     * Determine whether the user can delete the halaqa.
     */
    public function delete(User $user, Halaqa $halaqa): bool
    {
        return $this->view($user, $halaqa);
    }
}
