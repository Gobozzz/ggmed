<?php

declare(strict_types=1);

namespace App\Adapters\Logger;

use Illuminate\Support\Facades\Log;

final class LaravelLogger implements LoggerContract
{
    private ?array $channels = null;

    public function channels(array $channels): self
    {
        $this->channels = $channels;

        return $this;
    }

    public function emergency(string $message, array $context = []): void
    {
        Log::channel($this->channels)->emergency($message, $context);
    }

    public function alert(string $message, array $context = []): void
    {
        Log::channel($this->channels)->alert($message, $context);
    }

    public function critical(string $message, array $context = []): void
    {
        Log::channel($this->channels)->critical($message, $context);
    }

    public function error(string $message, array $context = []): void
    {
        Log::channel($this->channels)->error($message, $context);
    }

    public function warning(string $message, array $context = []): void
    {
        Log::channel($this->channels)->warning($message, $context);
    }

    public function notice(string $message, array $context = []): void
    {
        Log::channel($this->channels)->notice($message, $context);
    }

    public function info(string $message, array $context = []): void
    {
        Log::channel($this->channels)->info($message, $context);
    }

    public function debug(string $message, array $context = []): void
    {
        Log::channel($this->channels)->debug($message, $context);
    }
}
