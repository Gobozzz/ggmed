<?php

declare(strict_types=1);

namespace App\Enums\DTO\Transaction;

use App\Enums\TypeTransaction;

final readonly class CreateTransactionDTO
{
    public function __construct(
        public TypeTransaction $type,
        public float $amount,
        public int $user_id,
        public ?string $description = null,
        public array $metadata = [],
    ) {}
}
