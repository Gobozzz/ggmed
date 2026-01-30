<?php

declare(strict_types=1);

namespace App\MoonShine\Controllers\Transaction;

use App\DTO\Transaction\AdminWriteOffDTO;
use App\Models\User;
use App\Services\TransactionService\TransactionServiceContract;
use MoonShine\Contracts\Core\DependencyInjection\CrudRequestContract;
use MoonShine\Laravel\Http\Controllers\MoonShineController;
use Symfony\Component\HttpFoundation\Response;

final class WriteOffAdminController extends MoonShineController
{
    public function __invoke(CrudRequestContract $request, User $user, TransactionServiceContract $transactionService): Response
    {
        if (! $this->auth()->user()->isSuperUser()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        request()->validate([
            'amount' => ['required', 'numeric', 'min:'.config('transactions.min_amount_transaction'), 'max:'.config('transactions.max_amount_transaction')],
            'description' => 'nullable|string|max:500',
        ], [
            'amount.min' => 'Минимальная сумма списания: '.config('transactions.min_amount_transaction'),
            'amount.max' => 'Максимальная сумма списания: '.config('transactions.max_amount_transaction'),
            'description.max' => 'Максимальная длина комментария 500 символов',
        ]);

        $data = new AdminWriteOffDTO(
            user_id: $user->getKey(),
            admin_id: $this->auth()->id(),
            amount: (float) $request->get('amount'),
            description: $request->get('description'),
        );

        $transactionService->writeOffAdmin($data);

        return $this->json(message: 'Успешное списание у пользователя:  '.$user->name);
    }
}
