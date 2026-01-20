<?php

namespace App\Policies\MoonShine;

use App\Models\Comment;
use App\Models\MoonshineUser;

class CommentPolicy
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
    public function view(MoonshineUser $user, Comment $comment): bool
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
    public function update(MoonshineUser $user, Comment $comment): bool
    {
        return $user->isSuperUser();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(MoonshineUser $user, Comment $comment): bool
    {
        return $user->isSuperUser();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(MoonshineUser $user, Comment $comment): bool
    {
        return $user->isSuperUser();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(MoonshineUser $user, Comment $comment): bool
    {
        return $user->isSuperUser();
    }
}
