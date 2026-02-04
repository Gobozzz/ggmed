<?php

declare(strict_types=1);

namespace App\DTO\AI;

use App\Enums\AI\AiMessageRole;

final readonly class AiMessage
{
    public function __construct(
        public string $content,
        public AiMessageRole $role,
    ) {}

    public function toArray(): array
    {
        return [
            'content' => $this->content,
            'role' => $this->role->value,
        ];
    }
}
