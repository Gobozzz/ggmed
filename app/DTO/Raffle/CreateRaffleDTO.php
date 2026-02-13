<?php

declare(strict_types=1);

namespace App\DTO\Raffle;

use App\Enums\RaffleType;

final readonly class CreateRaffleDTO
{
    public function __construct(
        public RaffleType $type,
        public string $title,
        public string $description,
        public string $date_end,
        public ?string $content = null,
        public ?string $meta_title = null,
        public ?string $meta_description = null,
        public ?string $image = null,
        public ?array $prize = null,
    ) {}
}
