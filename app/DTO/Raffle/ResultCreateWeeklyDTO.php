<?php

declare(strict_types=1);

namespace App\DTO\Raffle;

final readonly class ResultCreateWeeklyDTO
{
    public function __construct(
        public bool            $success,
        public int|string|null $raffle_id = null,
        public ?string         $error_message = null,
    )
    {
    }
}
