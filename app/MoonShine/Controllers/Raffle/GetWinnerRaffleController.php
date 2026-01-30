<?php

declare(strict_types=1);

namespace App\MoonShine\Controllers\Raffle;

use App\Models\Raffle;
use Illuminate\View\View;
use MoonShine\Contracts\Core\DependencyInjection\CrudRequestContract;
use MoonShine\Laravel\Http\Controllers\MoonShineController;
use Symfony\Component\HttpFoundation\Response;

final class GetWinnerRaffleController extends MoonShineController
{
    public function __invoke(CrudRequestContract $request, Raffle $raffle): Response|View
    {
        if (! $this->auth()->user()->isSuperUser()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        return view('admin.modals.raffle-winner', ['winner' => $raffle->winner]);
    }
}
