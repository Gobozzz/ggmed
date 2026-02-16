<?php

declare(strict_types=1);

namespace App\DTO\Transaction;

use App\Enums\TypeTransaction;

final readonly class CreateTransactionDTO
{
    public function __construct(
        public TypeTransaction $type,
        public float $amount,
        public int|string $userId,
        public ?string $description = null,
        public array $metadata = [],
    ) {}
}
