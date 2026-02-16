<?php

declare(strict_types=1);

namespace App\MoonShine\Controllers\Transaction;

use App\DTO\Transaction\AdminWriteOffDTO;
use App\Models\Transaction;
use App\Models\User;
use App\Services\TransactionService\TransactionServiceContract;
use Illuminate\Support\Facades\Gate;
use MoonShine\Contracts\Core\DependencyInjection\CrudRequestContract;
use MoonShine\Laravel\Http\Controllers\MoonShineController;
use Symfony\Component\HttpFoundation\Response;

final class WriteOffAdminController extends MoonShineController
{
    public function __invoke(CrudRequestContract $request, User $user, TransactionServiceContract $transactionService): Response
    {
        Gate::authorize('write-off', Transaction::class);

        request()->validate([
            'amount' => ['required', 'numeric', 'min:'.config('transactions.min_amount_transaction'), 'max:'.config('transactions.max_amount_transaction')],
            'description' => 'nullable|string|max:500',
        ], [
            'amount.min' => 'Минимальная сумма списания: '.config('transactions.min_amount_transaction'),
            'amount.max' => 'Максимальная сумма списания: '.config('transactions.max_amount_transaction'),
            'description.max' => 'Максимальная длина комментария 500 символов',
        ]);

        $data = new AdminWriteOffDTO(
            userId: $user->getKey(),
            adminId: $this->auth()->id(),
            amount: (float) $request->get('amount'),
            description: $request->get('description'),
        );

        $transactionService->writeOffAdmin($data);

        return $this->json(message: 'Успешное списание у пользователя:  '.$user->name);
    }
}
