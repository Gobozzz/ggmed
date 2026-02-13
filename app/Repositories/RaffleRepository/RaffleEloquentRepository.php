<?php

declare(strict_types=1);

namespace App\Repositories\RaffleRepository;

use App\DTO\Raffle\CreateRaffleDTO;
use App\Enums\RaffleType;
use App\Models\Raffle;

final class RaffleEloquentRepository implements RaffleRepositoryContract
{
    public function create(CreateRaffleDTO $data): Raffle
    {
        return Raffle::query()->create([
            'type' => $data->type,
            'title' => $data->title,
            'description' => $data->description,
            'content' => $data->content,
            'date_end' => $data->date_end,
            'meta_title' => $data->meta_title,
            'meta_description' => $data->meta_description,
            'image' => $data->image,
            'prize' => $data->prize,
        ]);
    }

    public function getWeeklyReadyPlaying(): ?Raffle
    {
        return Raffle::query()->where('type', RaffleType::WEEKLY)
            ->where('date_end', '>=', now()->startOfDay())
            ->where('date_end', '<=', now()->endOfDay())
            ->whereNull('winner_id')
            ->first();
    }

    public function setWinner(int|string $user_id, int|string $raffle_id): bool
    {
        return (bool) Raffle::query()->where('id', $raffle_id)->update(['winner_id' => $user_id]);
    }

    public function deleteAllWeeklyUnplayed(): void
    {
        Raffle::query()->where('type', RaffleType::WEEKLY)
            ->where('date_end', '<', now()->startOfDay())
            ->whereNull('winner_id')->delete();
    }
}
