<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Fact;

use App\Models\Fact;
use App\MoonShine\Resources\Fact\Pages\FactDetailPage;
use App\MoonShine\Resources\Fact\Pages\FactFormPage;
use App\MoonShine\Resources\Fact\Pages\FactIndexPage;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Laravel\Resources\ModelResource;

/**
 * @extends ModelResource<Fact, FactIndexPage, FactFormPage, FactDetailPage>
 */
class FactResource extends ModelResource
{
    protected string $model = Fact::class;

    protected string $title = 'Факты';

    protected string $column = 'id';

    protected bool $withPolicy = true;

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            FactIndexPage::class,
            FactFormPage::class,
            FactDetailPage::class,
        ];
    }
}
