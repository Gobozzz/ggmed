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
        public \DateTime $dateEnd,
        public ?string $content = null,
        public ?string $metaTitle = null,
        public ?string $metaDescription = null,
        public ?string $image = null,
        public ?array $prize = null,
    ) {}
}
