<?php

declare(strict_types=1);

namespace App\Actions\Raffle;

use App\Models\User;
use App\Repositories\UserRepository\UserRepositoryContract;

final class SelectWinnerRaffleAction
{
    public function __construct(
        private readonly UserRepositoryContract $userRepository,
    ) {}

    public function execute(int|string $raffle_id): ?User
    {
        return $this->userRepository->getRandomParticipantForRaffle($raffle_id);
    }
}
