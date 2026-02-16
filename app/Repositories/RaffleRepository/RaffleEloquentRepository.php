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
            'date_end' => $data->dateEnd,
            'meta_title' => $data->metaTitle,
            'meta_description' => $data->metaDescription,
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

    public function setWinner(int $userId, int $raffleId): Raffle
    {
        $raffle = $this->findOrFail($raffleId);
        $raffle->updateOrFail(['winner_id' => $userId]);

        return $raffle->fresh();
    }

    public function deleteAllWeeklyUnplayed(): void
    {
        Raffle::query()->where('type', RaffleType::WEEKLY)
            ->where('date_end', '<', now()->startOfDay())
            ->whereNull('winner_id')->delete();
    }

    public function findOrFail(int $id): Raffle
    {
        return Raffle::query()->findOrFail($id);
    }
}
