<?php

declare(strict_types=1);

namespace App\Policies\MoonShine;

use App\Models\MoonshineUser;
use App\Models\Post;

class PostPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(MoonshineUser $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(MoonshineUser $user, Post $post): bool
    {
        return $user->isSuperUser() ||
            ($user->isFilialManagerUser() && $post->filial?->manager_id === $user->getKey()) ||
            ($user->isAuthorPostsUser() && $post->author_id === $user->getKey());
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(MoonshineUser $user): bool
    {
        return $user->isSuperUser() ||
            ($user->isFilialManagerUser() && $user->filials()->count() > 0) ||
            $user->isAuthorPostsUser();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(MoonshineUser $user, Post $post): bool
    {
        return $user->isSuperUser() ||
            $post->filial?->manager_id === $user->getKey() ||
            ($user->isAuthorPostsUser() && $post->author_id === $user->getKey());
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(MoonshineUser $user, Post $post): bool
    {
        return $user->isSuperUser() ||
            $post->filial?->manager_id === $user->getKey() ||
            ($user->isAuthorPostsUser() && $post->author_id === $user->getKey());
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(MoonshineUser $user, Post $post): bool
    {
        return $user->isSuperUser();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(MoonshineUser $user, Post $post): bool
    {
        return $user->isSuperUser();
    }

    public function massDelete(MoonshineUser $user): bool
    {
        return $user->isSuperUser();
    }
}
