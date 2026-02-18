<?php

declare(strict_types=1);

namespace App\Adapters\AiAssistant;

use App\DTO\AI\AiMessage;

interface AiAssistantContract
{
    public function sendRequest(array $messages): AiMessage;

    public function getRemainsTokens(): int;

    public function getPayLink(): string;
}
