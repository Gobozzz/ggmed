<?php

declare(strict_types=1);

namespace App\Logging;

use App\BotNotifiers\BotNotificatorContract;
use App\Enums\Bots\TypeBot;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;

final class BotLoggingHandler extends AbstractProcessingHandler
{
    private readonly TypeBot $bot;

    public function __construct(
        string $bot,
        private readonly BotNotificatorContract $botNotificator,
        int|string|Level $level = Level::Debug,
        bool $bubble = true
    ) {
        $bot = TypeBot::tryFrom($bot);

        if ($bot === null) {
            throw new \Exception('TypeBot is not valid');
        }
        $this->bot = $bot;

        parent::__construct($level, $bubble);
    }

    protected function write(LogRecord $record): void
    {
        $this->send($this->getFormattedMessage($record->message, $record->context, $record->level));
    }

    private function getFormattedMessage(string $message, array $context, Level $level): string
    {
        return "[{$level->getName()}]: ".$message."\n\n".json_encode($context, JSON_PRETTY_PRINT);
    }

    private function send(string $message): void
    {
        $this->botNotificator->bot($this->bot)->sendMessage($message);
    }
}
