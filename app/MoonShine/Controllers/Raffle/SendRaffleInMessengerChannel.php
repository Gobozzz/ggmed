<?php

declare(strict_types=1);

namespace App\MoonShine\Controllers\Raffle;

use App\BotNotifiers\BotNotificatorContract;
use App\Enums\Bots\TypeBot;
use App\Models\Raffle;
use Illuminate\Support\Facades\Storage;
use MoonShine\Contracts\Core\DependencyInjection\CrudRequestContract;
use MoonShine\Laravel\Http\Controllers\MoonShineController;
use Symfony\Component\HttpFoundation\Response;

final class SendRaffleInMessengerChannel extends MoonShineController
{
    public function __invoke(CrudRequestContract $request, Raffle $raffle, BotNotificatorContract $botNotificator): Response
    {

        $message = $botNotificator->bot(TypeBot::ADMIN_CHANNEL_BOT)
            ->parseModeHTML()
            ->withInlineKeyboards([
                [
                    ['text' => 'Перейти к розыгрышу', 'url' => 'https://ggmed.ru'],
                ],
            ]);

        if ($raffle->image) {
            $message->withImage(Storage::url($raffle->image));
        }

        $message->sendMessage($this->getFormattedMessage($raffle));

        return $this->json('Отправил сообщение в ТГ канал');
    }

    private function getFormattedMessage(Raffle $raffle): string
    {
        return '🎉 <b>Внимание, Розыгрыш!</b> 🎉' . "\n\n" .
            '🏆 ' . $raffle->title . "\n\n" .
            '📝 ' . $raffle->description . "\n\n" .
            '👉 Переходите по ссылке ниже, и выполняйте условия розыгрыша, они просты.' . "\n\n" .
            '⏰ Итоги розыгрыша подводим: ' . $raffle->date_end->locale('ru')->isoFormat('D MMMM Y') . "г.\n\n" .
            '🎁 <b>Учавствуйте, и возможо именно вы станете победителем розыгрыша!</b> ✨';
    }
}
