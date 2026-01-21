<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Filial;

use App\Models\Filial;
use App\MoonShine\Resources\Filial\Pages\FilialIndexPage;
use App\MoonShine\Resources\Filial\Pages\FilialFormPage;
use App\MoonShine\Resources\Filial\Pages\FilialDetailPage;

use Illuminate\Contracts\Database\Eloquent\Builder;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;

/**
 * @extends ModelResource<Filial, FilialIndexPage, FilialFormPage, FilialDetailPage>
 */
class FilialResource extends ModelResource
{
    protected string $model = Filial::class;

    protected string $title = 'Филиалы';

    protected string $column = 'name';

    protected bool $withPolicy = true;

    protected function search(): array
    {
        return ['id', 'name', 'slug'];
    }

    protected function modifyQueryBuilder(Builder $builder): Builder
    {
        return $builder->when(auth()->user()->isFilialManagerUser(), function (Builder $builder) {
            return $builder->where('manager_id', auth()->user()->getKey());
        });
    }

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            FilialIndexPage::class,
            FilialFormPage::class,
            FilialDetailPage::class,
        ];
    }
}
