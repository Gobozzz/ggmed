<?php

declare(strict_types=1);

namespace App\DTO\Transaction;

final readonly class WinningWeeklyRaffleDTO
{
    public function __construct(
        public int $userId,
        public int $raffleId,
        public float $amount,
        public ?string $description = null,
    ) {}
}
