<?php

declare(strict_types=1);

namespace App\MoonShine\Controllers\Raffle;

use App\Actions\Raffle\SelectWinnerRaffleAction;
use App\Models\Raffle;
use App\Repositories\RaffleRepository\RaffleRepositoryContract;
use Illuminate\Support\Facades\Gate;
use MoonShine\Contracts\Core\DependencyInjection\CrudRequestContract;
use MoonShine\Crud\Contracts\Notifications\MoonShineNotificationContract;
use MoonShine\Laravel\Http\Controllers\MoonShineController;
use Symfony\Component\HttpFoundation\Response;

final class SelectWinnerRaffleController extends MoonShineController
{
    public function __construct(
        MoonShineNotificationContract $notification,
        private readonly SelectWinnerRaffleAction $selectWinnerRaffleAction,
        private readonly RaffleRepositoryContract $raffleRepository,
    ) {
        parent::__construct($notification);
    }

    public function __invoke(CrudRequestContract $request, Raffle $raffle): Response
    {
        Gate::authorize('start', $raffle);

        $winner = $this->selectWinnerRaffleAction->execute($raffle->getKey());
        if ($winner === null) {
            return $this->json(message: 'Не нашлось победителя', status: Response::HTTP_NOT_FOUND);
        }
        try {
            $this->raffleRepository->setWinner($winner->getKey(), $raffle->getKey());
        } catch (\Exception $e) {
            return $this->json(message: 'Не удалось установить победителя', status: Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json(message: 'Победитель обнаружен', data: ['winner' => $winner]);
    }
}
