<?php

namespace App\Policies\MoonShine;

use App\Models\MoonshineUser;

class MoonshineUserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(MoonshineUser $user): bool
    {
        return $user->isSuperUser();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(MoonshineUser $user, MoonshineUser $moonshineUser): bool
    {
        return $user->isSuperUser();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(MoonshineUser $user): bool
    {
        return $user->isSuperUser();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(MoonshineUser $user, MoonshineUser $moonshineUser): bool
    {
        return $user->isSuperUser() && !$moonshineUser->isSuperUser();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(MoonshineUser $user, MoonshineUser $moonshineUser): bool
    {
        return $user->isSuperUser() && !$moonshineUser->isSuperUser();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(MoonshineUser $user, MoonshineUser $moonshineUser): bool
    {
        return $user->isSuperUser();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(MoonshineUser $user, MoonshineUser $moonshineUser): bool
    {
        return $user->isSuperUser();
    }
}
