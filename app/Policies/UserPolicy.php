<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return $user->role === 'admin'
            ? Response::allow()
            : Response::deny('You do not have permission to view users.');
    }

    /**
     * Determine if the authenticated user can view their profile.
     */
    public function view(User $authUser, User $userModel): Response
    {
        return $authUser->id === $userModel->id
            ? Response::allow()
            : Response::deny('You do not have permission to view this user.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine if the authenticated user can update their profile.
     */
    public function update(User $authUser, User $userModel): response
    {
        return $authUser->id === $userModel->id
            ? Response::allow()
            : Response::deny('You do not have permission to update this user.');
    }

    /**
     * Determine if the authenticated user can delete their account.
     */
    public function delete(User $authUser, User $userModel): Response
    {
        return $authUser->id === $userModel->id
            ? Response::allow()
            : Response::deny('You do not have permission to delete this user.');
    }

    /**
     * Determine if the authenticated user can manage other users (Admin-only).
     */
    public function manage(User $authUser)
    {
        return $authUser->role === 'admin'; // Only admins can manage users
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): Response
    {
        return Response::deny('You do not have permission to permanently delete this user.');
    }
}
