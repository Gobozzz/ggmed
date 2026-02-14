<?php

declare(strict_types=1);

namespace App\Services\RaffleService;

interface RaffleServiceContract
{
    public function createWeeklyRaffle(): void;

    public function playWeeklyRaffle(): void;
}
