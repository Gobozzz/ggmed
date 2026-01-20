<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use MoonShine\Laravel\Models\MoonshineUser as Admin;

class MoonshineUser extends Admin
{
    final public const FILIAL_MANAGER_ROLE_ID = 2;

    public function isFilialManagerUser(): bool
    {
        return $this->moonshine_user_role_id === self::FILIAL_MANAGER_ROLE_ID;
    }

}
