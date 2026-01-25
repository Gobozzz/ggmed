<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\StarGuest;

use App\Models\StarGuest;
use App\MoonShine\Resources\StarGuest\Pages\StarGuestDetailPage;
use App\MoonShine\Resources\StarGuest\Pages\StarGuestFormPage;
use App\MoonShine\Resources\StarGuest\Pages\StarGuestIndexPage;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Laravel\Resources\ModelResource;

/**
 * @extends ModelResource<StarGuest, StarGuestIndexPage, StarGuestFormPage, StarGuestDetailPage>
 */
class StarGuestResource extends ModelResource
{
    protected string $model = StarGuest::class;

    protected string $title = 'Звездные гости';

    protected string $column = 'name';

    protected bool $withPolicy = true;

    protected function search(): array
    {
        return ['id', 'name'];
    }

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            StarGuestIndexPage::class,
            StarGuestFormPage::class,
            StarGuestDetailPage::class,
        ];
    }
}
