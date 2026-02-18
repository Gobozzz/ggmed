<?php

declare(strict_types=1);

namespace App\Services\RaffleService;

interface RaffleServiceContract
{
    /**
     * @throws \Throwable
     */
    public function playWeeklyRaffle(): void;
}
