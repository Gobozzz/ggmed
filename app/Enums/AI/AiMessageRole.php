<?php

namespace App\Enums\AI;

enum AiMessageRole: string
{
    case SYSTEM = 'system';
    case USER = 'user';
    case ASSISTANT = 'assistant';
}
