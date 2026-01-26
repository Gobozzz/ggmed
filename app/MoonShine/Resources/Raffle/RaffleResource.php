<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Raffle;

use App\Models\Raffle;
use App\MoonShine\Resources\Raffle\Pages\RaffleDetailPage;
use App\MoonShine\Resources\Raffle\Pages\RaffleFormPage;
use App\MoonShine\Resources\Raffle\Pages\RaffleIndexPage;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Laravel\Resources\ModelResource;

/**
 * @extends ModelResource<Raffle, RaffleIndexPage, RaffleFormPage, RaffleDetailPage>
 */
class RaffleResource extends ModelResource
{
    protected string $model = Raffle::class;

    protected string $title = 'Розыгрыши';

    protected string $column = 'title';

    protected bool $withPolicy = true;

    protected array $with = ['winner', 'tags', 'comments', 'likes'];

    protected function search(): array
    {
        return ['id', 'title'];
    }

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            RaffleIndexPage::class,
            RaffleFormPage::class,
            RaffleDetailPage::class,
        ];
    }
}
