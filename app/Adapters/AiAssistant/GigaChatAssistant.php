<?php

declare(strict_types=1);

namespace App\Adapters\AiAssistant;

use App\DTO\AI\AiMessage;
use App\Enums\AI\AiMessageRole;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

final class GigaChatAssistant implements AiAssistantContract
{
    const TIMEOUT_REQUEST = 60;

    public function sendRequest(array $messages): AiMessage
    {
        $token = $this->getAccessToken();

        $messages = array_map(fn(AiMessage $message) => $message->toArray(), $messages);

        try {
            $response = Http::timeout(self::TIMEOUT_REQUEST)->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => "Bearer {$token}",
            ])->withOptions([
                'verify' => $this->getCertificate(),
            ])->post($this->getApiUrl() . '/chat/completions', [
                'model' => config('services.giga_chat.model'),
                'messages' => $messages,
            ]);
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['choices'][0]['message']['content']) && is_string($data['choices'][0]['message']['content'])) {
                    return new AiMessage(content: $data['choices'][0]['message']['content'], role: AiMessageRole::ASSISTANT);
                }

                throw new \Exception("Incorrect data format");
            } else {
                throw new \Exception('The request ended with an error');
            }
        } catch (\Exception $e) {
            Log::error('Send Request AiAssistant Error: ' . $e->getMessage());

            throw $e;
        }
    }

    public function getRemainsTokens(): int
    {
        $token = $this->getAccessToken();

        try {
            $response = Http::timeout(self::TIMEOUT_REQUEST)->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => "Bearer {$token}",
            ])->withOptions([
                'verify' => $this->getCertificate(),
            ])->get($this->getApiUrl() . '/balance');

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['balance'])) {
                    $gigaChatItem = array_reduce($data['balance'], function ($carry, $item) {
                        return $item['usage'] === config('services.giga_chat.model_name_for_balance') ? $item : $carry;
                    });

                    return $gigaChatItem['value'];
                } else {
                    throw new \Exception("Incorrect data format");
                }
            } else {
                throw new \Exception('Не удалось получить ответ от Giga Chat: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('AiAssistant Get Remains Token Error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getPayLink(): string
    {
        return config('services.giga_chat.pay_link', '');
    }

    /**
     * @throws ConnectionException|\Exception
     */
    private function getAccessToken(): string
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Accept' => 'application/json',
            'RqUID' => Str::uuid()->toString(),
            'Authorization' => 'Basic ' . config('services.giga_chat.key'),
        ])
            ->asForm()
            ->withOptions([
                'verify' => $this->getCertificate(),
            ])->post($this->getAuthUrl(), [
                'scope' => config('services.giga_chat.scope'),
            ]);
        if ($response->successful()) {
            $data = $response->json();

            return $data['access_token'];
        } else {
            throw new \Exception('Error Get Access Token');
        }
    }

    private function getApiUrl(): string
    {
        return config('services.giga_chat.api_url');
    }

    private function getAuthUrl(): string
    {
        return config('services.giga_chat.auth_url');
    }

    private function getCertificate(): string
    {
        return storage_path(config('services.giga_chat.certificate_path'));
    }
}
