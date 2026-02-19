<?php

declare(strict_types=1);

namespace App\Adapters\AiAssistant;

use App\DTO\AI\AiMessage;
use App\Enums\AI\AiMessageRole;
use App\Exceptions\AiAssistant\AiAuthenticationException;
use App\Exceptions\AiAssistant\AiInvalidDataResponseException;
use App\Exceptions\AiAssistant\AiInvalidResponseException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

final class GigaChatAssistant implements AiAssistantContract
{
    public function sendRequest(array $messages): AiMessage
    {
        $messages = array_map(fn (AiMessage $message) => $message->toArray(), $messages);

        $response = $this->createHttpClient()->post($this->getApiUrl().'/chat/completions', [
            'model' => $this->getCurrentModel(),
            'messages' => $messages,
        ]);
        if ($response->successful()) {
            return $this->parseResponseForRequest($response->json());
        }
        throw new AiInvalidResponseException;
    }

    public function getRemainsTokens(): int
    {
        $response = $this->createHttpClient()->get($this->getApiUrl().'/balance');

        if ($response->successful()) {
            $data = $response->json();

            return $this->parseBalanceTokens($data);
        }
        throw new AiInvalidResponseException;
    }

    public function getPayLink(): string
    {
        return config('services.giga_chat.pay_link', '');
    }

    private function parseResponseForRequest(mixed $data): AiMessage
    {
        if (
            is_array($data) &&
            isset($data['choices'][0]['message']['content']) &&
            is_string($data['choices'][0]['message']['content'])
        ) {
            return new AiMessage(content: $data['choices'][0]['message']['content'], role: AiMessageRole::ASSISTANT);
        }

        throw new AiInvalidDataResponseException;
    }

    private function parseBalanceTokens(mixed $data): int
    {
        if (is_array($data) && isset($data['balance']) && is_array($data['balance'])) {
            $balance = $data['balance'];
            $modelName = config('services.giga_chat.model_name_for_balance');
            foreach ($balance as $item) {
                if (! isset($item['usage']) || ! isset($item['value'])) {
                    break;
                }
                if ($item['usage'] === $modelName) {
                    return (int) $item['value'];
                }
            }
        }

        throw new AiInvalidDataResponseException;
    }

    private function createHttpClient(): PendingRequest
    {
        $token = $this->getAccessToken();

        return Http::timeout(config('services.giga_chat.timeout_request_seconds', 30))
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => "Bearer {$token}",
            ])
            ->withOptions([
                'verify' => $this->getCertificatePath(),
            ]);
    }

    private function getAccessToken(): string
    {
        return Cache::remember('giga_chat_token', now()->addMinutes(25), function () {
            return $this->fetchAccessToken();
        });
    }

    private function fetchAccessToken(): string
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Accept' => 'application/json',
            'RqUID' => Str::uuid()->toString(),
            'Authorization' => 'Basic '.config('services.giga_chat.key'),
        ])
            ->asForm()
            ->withOptions([
                'verify' => $this->getCertificatePath(),
            ])->post($this->getAuthUrl(), [
                'scope' => config('services.giga_chat.scope'),
            ]);
        if ($response->successful()) {
            $data = $response->json();

            if (! isset($data['access_token'])) {
                throw new AiInvalidDataResponseException;
            }

            return $data['access_token'];
        }
        throw new AiAuthenticationException;
    }

    private function getCurrentModel(): string
    {
        return config('services.giga_chat.model', '');
    }

    private function getApiUrl(): string
    {
        return config('services.giga_chat.api_url', '');
    }

    private function getAuthUrl(): string
    {
        return config('services.giga_chat.auth_url', '');
    }

    private function getCertificatePath(): string
    {
        return storage_path(config('services.giga_chat.certificate_path', ''));
    }
}
