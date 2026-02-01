<?php

declare(strict_types=1);

namespace App\Repositories\UserRepository;

interface UserRepositoryContract
{
    public function lockForUpdateById(int $user_id): void;
}
