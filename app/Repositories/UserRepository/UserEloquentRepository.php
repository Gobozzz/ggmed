<?php

declare(strict_types=1);

namespace App\Repositories\UserRepository;

use App\Models\Raffle;
use App\Models\User;

final class UserEloquentRepository implements UserRepositoryContract
{
    public function lockForUpdateById(int|string $user_id): void
    {
        User::query()->where('id', $user_id)->lockForUpdate()->exists();
    }

    public function getRandomParticipantForRaffle(int|string $raffle_id): ?User
    {
        return User::query()->actived()
            ->whereHas('comments', function ($query) use ($raffle_id) {
                $query->where('commentable_type', Raffle::class)
                    ->where('commentable_id', $raffle_id);
            })->inRandomOrder()->first();
    }
}
