<?php

declare(strict_types=1);

namespace App\Repositories\UserRepository;

use App\Models\User;

interface UserRepositoryContract
{
    public function lockForUpdateById(int|string $user_id): void;

    public function getRandomParticipantForRaffle(int|string $raffle_id): ?User;
}
