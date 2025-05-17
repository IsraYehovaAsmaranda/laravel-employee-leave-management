<?php

namespace App\Policies;

use App\Models\IntervieweeTask;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class IntervieweeTaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo("read-intervieweeTask");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, IntervieweeTask $intervieweeTask): bool
    {
        return $user->hasPermissionTo("read-intervieweeTask");
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo("create-intervieweeTask");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, IntervieweeTask $intervieweeTask): bool
    {
        return $user->hasPermissionTo("update-intervieweeTask");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, IntervieweeTask $intervieweeTask): bool
    {
        return $user->hasPermissionTo("delete-intervieweeTask");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, IntervieweeTask $intervieweeTask): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, IntervieweeTask $intervieweeTask): bool
    {
        return false;
    }
}
