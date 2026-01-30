<?php

declare(strict_types=1);

namespace App\Repositories\UserRepository;

use App\Models\User;

interface UserRepositoryContract
{
    public function getByIdForUpdate(int $user_id): User;
}
