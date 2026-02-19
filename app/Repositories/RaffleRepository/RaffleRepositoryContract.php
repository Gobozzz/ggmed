<?php

declare(strict_types=1);

namespace App\Repositories\RaffleRepository;

use App\DTO\Raffle\CreateRaffleDTO;
use App\Models\Raffle;

interface RaffleRepositoryContract
{
    public function create(CreateRaffleDTO $data): Raffle;

    public function getWeeklyReadyPlayingNow(): ?Raffle;

    public function setWinner(int $userId, int $raffleId): Raffle;

    public function deleteAllWeeklyUnplayed(): void;

    public function findOrFail(int $id): Raffle;
}
