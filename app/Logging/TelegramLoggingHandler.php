<?php

declare(strict_types=1);

namespace App\Logging;

use App\Enums\ChannelLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;

final class TelegramLoggingHandler extends AbstractProcessingHandler
{
    const MAX_LENGTH_MESSAGE = 4096;

    private array $chat_ids;

    public function __construct(
        private readonly string $bot_token,
        string $chat_ids,
        int|string|Level $level = Level::Debug,
        bool $bubble = true
    ) {
        parent::__construct($level, $bubble);
        $this->chat_ids = explode(',', $chat_ids);
    }

    protected function write(LogRecord $record): void
    {
        $this->send($this->getFormattedMessage($record->message, $record->context, $record->level));
    }

    private function getFormattedMessage(string $message, array $context, Level $level): string
    {
        $message = "[{$level->getName()}]: ".$message."\n\n".json_encode($context, JSON_PRETTY_PRINT);

        return substr($message, 0, self::MAX_LENGTH_MESSAGE);
    }

    private function getUrl(): string
    {
        return "https://api.telegram.org/bot{$this->bot_token}/sendMessage";
    }

    private function send(string $message): void
    {
        foreach ($this->chat_ids as $chat_id) {
            try {
                Http::post($this->getUrl(), [
                    'chat_id' => $chat_id,
                    'text' => $message,
                ]);
            } catch (\Exception $e) {
                Log::channel(ChannelLog::FILE->value)->info('Telegram Log Error: '.$e->getMessage());
            }
        }
    }
}
