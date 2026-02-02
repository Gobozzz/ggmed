<?php

declare(strict_types=1);

namespace App\Enums\DTO\Transaction;

final readonly class AdminReplenishedPayDTO
{
    public function __construct(
        public int $user_id,
        public int $admin_id,
        public float $amount,
        public ?string $description = null,
    ) {}
}
