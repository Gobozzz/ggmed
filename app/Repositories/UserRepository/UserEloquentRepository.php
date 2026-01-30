<?php

declare(strict_types=1);

namespace App\Repositories\UserRepository;

use App\Models\User;

class UserEloquentRepository implements UserRepositoryContract
{
    public function getByIdForUpdate(int $user_id): User
    {
        return User::query()->where('id', $user_id)->lockForUpdate()->firstOrFail();
    }
}
