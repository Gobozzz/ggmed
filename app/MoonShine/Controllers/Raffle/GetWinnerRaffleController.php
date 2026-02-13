<?php

declare(strict_types=1);

namespace App\MoonShine\Controllers\Raffle;

use App\Models\Raffle;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use MoonShine\Contracts\Core\DependencyInjection\CrudRequestContract;
use MoonShine\Laravel\Http\Controllers\MoonShineController;
use Symfony\Component\HttpFoundation\Response;

final class GetWinnerRaffleController extends MoonShineController
{
    public function __invoke(CrudRequestContract $request, Raffle $raffle): Response|View
    {
        Gate::authorize('start', $raffle);

        return view('admin.modals.raffle-winner', ['winner' => $raffle->winner]);
    }
}
