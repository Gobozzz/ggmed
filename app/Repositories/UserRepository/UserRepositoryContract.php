<?php

declare(strict_types=1);

namespace App\Repositories\UserRepository;

use App\Models\User;

interface UserRepositoryContract
{
    public function getByIdAndLock(int $userId): ?User;

    public function getRandomParticipantForRaffle(int $raffleId): ?User;
}
