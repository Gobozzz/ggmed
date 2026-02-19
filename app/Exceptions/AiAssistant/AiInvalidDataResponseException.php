<?php

declare(strict_types=1);

namespace App\Exceptions\AiAssistant;

use App\Enums\Exceptions\AiAssistantException;
use App\Exceptions\BaseException;

final class AiInvalidDataResponseException extends BaseException
{
    public function __construct()
    {
        parent::__construct('AI Invalid Response', AiAssistantException::INVALID_RESPONSE_DATA->value);
    }
}
