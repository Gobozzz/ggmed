<?php

declare(strict_types=1);

namespace App\BotNotifiers;

use App\Enums\Bots\TypeBot;

interface BotNotificatorContract
{
    public function sendMessage(string $message): bool;

    public function bot(TypeBot $bot): self;

    public function parseModeMarkDown(): self;

    public function parseModeHTML(): self;

    public function withImage(string $url): self;

    /**
     * @param array $items Пример
     *  [
     *      ['text' => "Перейти", 'url' => "https://example.com"]
     *  ]
     */
    public function withInlineKeyboards(array $items): self;
}
