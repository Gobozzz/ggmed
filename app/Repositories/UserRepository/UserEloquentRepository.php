<?php

declare(strict_types=1);

namespace App\Repositories\UserRepository;

use App\Models\User;

final class UserEloquentRepository implements UserRepositoryContract
{
    public function lockForUpdateById(int $user_id): void
    {
        User::query()->where('id', $user_id)->lockForUpdate()->exists();
    }
}
