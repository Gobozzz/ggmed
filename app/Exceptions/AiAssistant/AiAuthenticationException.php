<?php

declare(strict_types=1);

namespace App\Exceptions\AiAssistant;

use App\Enums\Exceptions\AiAssistantException;
use App\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response;

final class AiAuthenticationException extends BaseException
{
    public function __construct()
    {
        parent::__construct('Ai Authentication failed', AiAssistantException::AUTHENTICATION_ERROR->value, Response::HTTP_UNAUTHORIZED);
    }
}
