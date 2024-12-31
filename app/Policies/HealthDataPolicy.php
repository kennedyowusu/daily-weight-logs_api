<?php

namespace App\Policies;

use App\Models\HealthData;
use App\Models\User;

class HealthDataPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine if the authenticated user can view their health data.
     */
    public function view(User $user, HealthData $healthData): bool
    {
        return $user->role === 'admin' || $user->id === $healthData->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine if the authenticated user can update their health data.
     */
    public function update(User $user, HealthData $healthData): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return $user->id === $healthData->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, HealthData $healthData): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return $user->id === $healthData->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, HealthData $healthData): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, HealthData $healthData): bool
    {
        return false;
    }
}
