<?php

namespace App\Policies;

use App\Models\Interviewee;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class IntervieweePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo("read-interviewee");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Interviewee $interviewee): bool
    {
        return $user->hasPermissionTo("read-interviewee");
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo("create-interviewee");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Interviewee $interviewee): bool
    {
        return $user->hasPermissionTo("update-interviewee");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Interviewee $interviewee): bool
    {
        return $user->hasPermissionTo("delete-interviewee");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Interviewee $interviewee): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Interviewee $interviewee): bool
    {
        return false;
    }
}
