<?php

declare(strict_types=1);

namespace App\Adapters\AiAssistant;

use App\DTO\AI\AiMessage;
use App\Exceptions\AiAssistant\AiAuthenticationException;
use App\Exceptions\AiAssistant\AiInvalidResponseException;
use Illuminate\Http\Client\ConnectionException;

interface AiAssistantContract
{
    /**
     * @param AiMessage[] $messages
     *
     * @throws ConnectionException|AiAuthenticationException|AiInvalidResponseException
     */
    public function sendRequest(array $messages): AiMessage;

    /**
     * @throws ConnectionException|AiAuthenticationException|AiInvalidResponseException
     */
    public function getRemainsTokens(): int;

    public function getPayLink(): string;
}
