<?php

declare(strict_types=1);

namespace App\DTO\Raffle;

final readonly class ResultSetWinnerWeeklyDTO
{
    public function __construct(
        public bool $success,
        public int|string $winner_id,
        public int|string $raffle_id,
        public ?float $amount = null,
        public ?string $error_message = null,
    ) {}
}
