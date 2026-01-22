<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use MoonShine\Laravel\Models\MoonshineUser as Admin;

class MoonshineUser extends Admin
{
    final public const FILIAL_MANAGER_ROLE_ID = 2;
    final public const AUTHOR_POSTS_ROLE_ID = 3;

    public function isFilialManagerUser(): bool
    {
        return $this->moonshine_user_role_id === self::FILIAL_MANAGER_ROLE_ID;
    }

    public function isAuthorPostsUser(): bool
    {
        return $this->moonshine_user_role_id === self::AUTHOR_POSTS_ROLE_ID;
    }

    public function filials(): HasMany
    {
        return $this->hasMany(Filial::class, 'manager_id');
    }

}
