<?php

declare(strict_types=1);

namespace App\Logging;

use App\Adapters\Logger\LoggerContract;
use Illuminate\Support\Facades\Http;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;

final class TelegramLoggingHandler extends AbstractProcessingHandler
{
    const MAX_LENGTH_MESSAGE = 4096;

    public function __construct(
        private LoggerContract $loger,
        private string         $bot_token,
        private string         $chat_id,
        int|string|Level       $level = Level::Debug,
        bool                   $bubble = true
    )
    {
        parent::__construct($level, $bubble);
    }

    protected function write(LogRecord $record): void
    {
        $this->send($this->getFormattedMessage($record->message, $record->context, $record->level));
    }

    private function getFormattedMessage(string $message, array $context, Level $level): string
    {
        $message = "[{$level->getName()}]: " . $message . "\n\n" . json_encode($context, JSON_PRETTY_PRINT);
        return substr($message, 0, self::MAX_LENGTH_MESSAGE);
    }

    private function getUrl(): string
    {
        return "https://api.telegram.org/bot{$this->bot_token}/sendMessage";
    }

    private function send(string $message): void
    {
        try {
            Http::post($this->getUrl(), [
                'chat_id' => $this->chat_id,
                'text' => $message,
            ]);
        } catch (\Exception $e) {
            // Если оставлю этот код, то вызовы зациклятся
            //
            // $this->loger->error("Ошибка запроса к ТГ:" . $e->getMessage());
        }
    }

}
