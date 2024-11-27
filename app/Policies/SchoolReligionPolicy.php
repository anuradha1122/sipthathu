<?php

namespace App\Policies;

use App\Models\SchoolReligion;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SchoolReligionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SchoolReligion $schoolReligion): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SchoolReligion $schoolReligion): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SchoolReligion $schoolReligion): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SchoolReligion $schoolReligion): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SchoolReligion $schoolReligion): bool
    {
        //
    }
}
