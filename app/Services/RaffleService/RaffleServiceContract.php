<?php

declare(strict_types=1);

namespace App\Services\RaffleService;

interface RaffleServiceContract
{
    public function createWeekly();

    public function playWeeklyRaffle(): void;
}
