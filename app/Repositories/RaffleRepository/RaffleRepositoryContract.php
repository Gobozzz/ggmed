<?php

declare(strict_types=1);

namespace App\Repositories\RaffleRepository;

use App\DTO\Raffle\CreateRaffleDTO;
use App\Models\Raffle;

interface RaffleRepositoryContract
{
    public function create(CreateRaffleDTO $data): Raffle;

    public function getWeeklyReadyPlaying(): ?Raffle;

    public function setWinner(int|string $user_id, int|string $raffle_id): bool;

    public function deleteAllWeeklyUnplayed(): void;
}
