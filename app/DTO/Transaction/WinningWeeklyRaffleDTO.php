<?php

declare(strict_types=1);

namespace App\DTO\Transaction;

final readonly class WinningWeeklyRaffleDTO
{
    public function __construct(
        public int|string $user_id,
        public int|string $raffle_id,
        public float $amount,
        public ?string $description = null,
    ) {}
}
