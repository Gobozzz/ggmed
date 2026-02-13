<?php

declare(strict_types=1);

namespace App\BotNotifiers;

use App\Enums\Bots\TypeBot;
use App\Enums\ChannelLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class TelegramBotNotificator implements BotNotificatorContract
{
    const MAX_LENGTH_MESSAGE = 4096;

    const MAX_LENGTH_MESSAGE_WITH_IMAGE = 1024;

    private ?TypeBot $bot = null;

    private ?string $parseMode = null;

    private ?string $imageUrl = null;

    private ?array $inlineKeyboards = null;

    public function sendMessage(string $message): bool
    {
        $this->checkBotData();

        $chat_ids = $this->getChatIds();

        $data = $this->prepareData($message);

        foreach ($chat_ids as $chat_id) {
            $data['chat_id'] = $chat_id;
            try {
                $response = Http::post($this->getApiUrlEndpoint(), $data);
                if (! $response->successful()) {
                    throw new \Exception("Couldn't send message: ".$response->body());
                }
            } catch (\Exception $e) {
                Log::channel(ChannelLog::FILE->value)->error('Telegram Bot Send Message Error: '.$e->getMessage());

                return false;
            }
        }

        return true;
    }

    public function bot(TypeBot $bot): self
    {
        $this->bot = $bot;

        return $this;
    }

    public function parseModeMarkDown(): self
    {
        $this->parseMode = 'MarkdownV2';

        return $this;
    }

    public function parseModeHTML(): self
    {
        $this->parseMode = 'HTML';

        return $this;
    }

    public function withImage(string $url): self
    {
        $this->imageUrl = $url;

        return $this;
    }

    public function withInlineKeyboards(array $items): self
    {
        $this->inlineKeyboards = $items;

        return $this;
    }

    private function prepareData(string $message): array
    {
        $data = [];

        $message = $this->formattedMessage($message);

        if ($this->imageUrl === null) {
            $data['text'] = $message;
        } else {
            $data['photo'] = $this->imageUrl;
            $data['caption'] = $message;
        }

        if ($this->parseMode !== null) {
            $data['parse_mode'] = $this->parseMode;
        }

        if ($this->inlineKeyboards !== null) {
            $data['reply_markup'] = [
                'inline_keyboard' => $this->inlineKeyboards,
            ];
        }

        return $data;
    }

    private function checkBotData(): void
    {
        if ($this->bot === null) {
            throw new \Exception('Telegram bot not set');
        }
    }

    private function getBaseApiUrl(): string
    {
        return "https://api.telegram.org/bot{$this->getBotToken()}";
    }

    private function getApiUrlEndpoint(): string
    {
        $baseUrl = $this->getBaseApiUrl();
        if ($this->imageUrl !== null) {
            return $baseUrl.'/sendPhoto';
        }

        return $baseUrl.'/sendMessage';
    }

    private function getBotToken(): string
    {
        $token = config('services.bots.'.$this->bot->value.'.token');
        if ($token === null) {
            throw new \Exception('Telegram Bot Token is not configured');
        }

        return $token;
    }

    private function getChatIds(): array
    {
        $chat_ids = config('services.bots.'.$this->bot->value.'.chat_ids');
        if ($chat_ids === null) {
            throw new \Exception('Telegram Bot Chat_ids is not configured');
        }

        return explode(',', $chat_ids);
    }

    private function formattedMessage(string $message): string
    {
        if ($this->imageUrl === null) {
            return mb_substr($message, 0, self::MAX_LENGTH_MESSAGE);
        } else {
            return mb_substr($message, 0, self::MAX_LENGTH_MESSAGE_WITH_IMAGE);
        }
    }
}
