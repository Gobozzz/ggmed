<?php

declare(strict_types=1);

namespace App\DTO\Transaction;

final readonly class AdminWriteOffDTO
{
    public function __construct(
        public int $userId,
        public int $adminId,
        public float $amount,
        public ?string $description = null,
    ) {}
}
