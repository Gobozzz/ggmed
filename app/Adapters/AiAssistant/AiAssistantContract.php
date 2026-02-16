<?php

declare(strict_types=1);

namespace App\Adapters\AiAssistant;

use App\DTO\AI\AiMessage;
use Illuminate\Http\Client\ConnectionException;

interface AiAssistantContract
{
    /**
     * @param  AiMessage[]  $messages
     *
     * @throws ConnectionException|\Exception
     */
    public function sendRequest(array $messages): AiMessage;

    /**
     * @throws ConnectionException|\Exception
     */
    public function getRemainsTokens(): int;

    public function getPayLink(): string;
}
