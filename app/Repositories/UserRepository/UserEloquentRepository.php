<?php

declare(strict_types=1);

namespace App\Repositories\UserRepository;

use App\Models\Raffle;
use App\Models\User;

final class UserEloquentRepository implements UserRepositoryContract
{
    public function getByIdAndLock(int $userId): ?User
    {
        return User::query()->lockForUpdate()->find($userId);
    }

    public function getRandomParticipantForRaffle(int $raffleId): ?User
    {
        return User::query()->actived()
            ->select('users.*')
            ->join('comments', 'users.id', '=', 'comments.user_id')
            ->where('comments.commentable_type', Raffle::class)
            ->where('comments.commentable_id', $raffleId)
            ->inRandomOrder()
            ->first();
    }
}
