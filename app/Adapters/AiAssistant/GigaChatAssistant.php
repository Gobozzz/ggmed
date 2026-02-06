<?php

declare(strict_types=1);

namespace App\Adapters\AiAssistant;

use App\DTO\AI\AiMessage;
use App\Enums\AI\AiMessageRole;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

final class GigaChatAssistant implements AiAssistantContract
{
    const AUTH_URL = 'https://ngw.devices.sberbank.ru:9443/api/v2/oauth';

    const API_URL = 'https://gigachat.devices.sberbank.ru/api/v1';

    public function sendRequest(array $messages): ?AiMessage
    {
        $token = $this->getAccessToken();

        $messages = array_map(fn (AiMessage $message) => $message->toArray(), $messages);

        try {
            $response = Http::timeout(60)->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => "Bearer {$token}",
            ])->withOptions([
                'verify' => $this->getCertificate(),
            ])->post(self::API_URL.'/chat/completions', [
                'model' => config('services.giga_chat.model'),
                'messages' => $messages,
            ]);
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['choices'][0]['message']['content']) && is_string($data['choices'][0]['message']['content'])) {
                    return new AiMessage(content: $data['choices'][0]['message']['content'], role: AiMessageRole::ASSISTANT);
                }

                return null;
            } else {
                throw new \Exception('Не удалось получить ответ от Giga Chat');
            }
        } catch (\Exception $e) {
            throw new \Exception('Giga Chat Get Answer Error: '.$e->getMessage());
        }
    }

    public function getRemainsTokens(): ?int
    {
        $token = $this->getAccessToken();

        try {
            $response = Http::timeout(60)->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => "Bearer {$token}",
            ])->withOptions([
                'verify' => $this->getCertificate(),
            ])->get(self::API_URL.'/balance');

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['balance'])) {
                    $gigaChatItem = array_reduce($data['balance'], function ($carry, $item) {
                        return $item['usage'] === config('services.giga_chat.model_name_for_balance') ? $item : $carry;
                    });

                    return $gigaChatItem['value'] ?? null;
                }
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getPayLink(): string
    {
        return config('services.giga_chat.pay_link', '');
    }

    private function getAccessToken(): string
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Accept' => 'application/json',
                'RqUID' => Str::uuid()->toString(),
                'Authorization' => 'Basic '.config('services.giga_chat.key'),
            ])
                ->asForm()
                ->withOptions([
                    'verify' => $this->getCertificate(),
                ])->post(self::AUTH_URL, [
                    'scope' => config('services.giga_chat.scope'),
                ]);
            if ($response->successful()) {
                $data = $response->json();

                return $data['access_token'];
            } else {
                throw new \Exception('Ошибка получения Access Token');
            }
        } catch (\Exception $exception) {
            throw new \Exception('Giga Chat Auth Error: '.$exception->getMessage());
        }
    }

    private function getCertificate(): string
    {
        return storage_path(config('services.giga_chat.certificate_path'));
    }
}
