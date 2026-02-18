<?php

declare(strict_types=1);

namespace App\Exceptions\AiAssistant;

use App\Enums\Exceptions\AiAssistantException;
use App\Exceptions\BaseException;

final class AiInvalidResponseException extends BaseException
{
    public function __construct()
    {
        parent::__construct("AI Invalid Response", AiAssistantException::INVALID_RESPONSE->value);
    }
}
