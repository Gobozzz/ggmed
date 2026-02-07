<?php

declare(strict_types=1);

namespace App\MoonShine\Controllers\Raffle;

use App\Enums\UserStatus;
use App\Models\Raffle;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use MoonShine\Contracts\Core\DependencyInjection\CrudRequestContract;
use MoonShine\Laravel\Http\Controllers\MoonShineController;
use Symfony\Component\HttpFoundation\Response;

final class SelectWinnerRaffleController extends MoonShineController
{
    public function __invoke(CrudRequestContract $request, Raffle $raffle): Response
    {
        Gate::authorize('start', Raffle::class);

        $winner = $this->getWinner($raffle);
        if ($winner === null) {
            return $this->json(message: 'Не нашлось победителя', status: Response::HTTP_NOT_FOUND);
        }
        if ($this->setWinner($raffle, $winner)) {
            return $this->json(message: 'Победитель обнаружен', data: ['winner' => $winner]);
        }

        return $this->json(message: 'Не удалось установить победителя', status: Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    private function getWinner(Raffle $raffle): ?User
    {
        return User::query()->whereHas('comments', function ($query) use ($raffle) {
            $query->where('commentable_type', Raffle::class)
                ->where('commentable_id', $raffle->getKey());
        })
            ->where('status', UserStatus::ACTIVED)
            ->inRandomOrder()
            ->first();
    }

    private function setWinner(Raffle $raffle, User $user): bool
    {
        $raffle->winner_id = $user->getKey();

        return $raffle->save();
    }
}
