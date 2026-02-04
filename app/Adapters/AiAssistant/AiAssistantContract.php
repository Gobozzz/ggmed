<?php

declare(strict_types=1);

namespace App\Adapters\AiAssistant;

use App\DTO\AI\AiMessage;

interface AiAssistantContract
{
    /**
     * @param  AiMessage[]  $messages
     */
    public function sendRequest(array $messages): ?AiMessage;
}
