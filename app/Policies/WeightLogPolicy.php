<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WeightLog;

class WeightLogPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Allow both admins and regular users to access their logs
        return $user->role === 'admin' || $user->role === 'user';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, WeightLog $weightLog)
    {
        return $user->role === 'admin' || $user->id === $weightLog->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'user';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, WeightLog $weightLog): bool
    {
         // Admins can update any weight log
        if ($user->role === 'admin') {
            return true;
        }

        // Regular users can only update their own weight logs
        return $user->id === $weightLog->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WeightLog $weightLog): bool
    {
        return $user->role === 'admin' || $user->id === $weightLog->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, WeightLog $weightLog): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, WeightLog $weightLog): bool
    {
        return false;
    }
}
