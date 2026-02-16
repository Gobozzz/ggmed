<?php

declare(strict_types=1);

namespace App\Exceptions\AiAssistant;

final class AiAuthenticationException extends AiAssistantException
{
    public function __construct(string $message = "Ai Authentication failed")
    {
        parent::__construct($message);
    }
}
