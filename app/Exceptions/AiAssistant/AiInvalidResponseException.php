<?php

declare(strict_types=1);

namespace App\Exceptions\AiAssistant;
final class AiInvalidResponseException extends AiAssistantException
{
    public function __construct(string $message = "Ai Invalid Response")
    {
        parent::__construct($message);
    }
}
