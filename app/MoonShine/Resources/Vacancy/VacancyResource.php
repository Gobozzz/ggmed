<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Vacancy;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Vacancy;
use App\MoonShine\Resources\Vacancy\Pages\VacancyIndexPage;
use App\MoonShine\Resources\Vacancy\Pages\VacancyFormPage;
use App\MoonShine\Resources\Vacancy\Pages\VacancyDetailPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;

/**
 * @extends ModelResource<Vacancy, VacancyIndexPage, VacancyFormPage, VacancyDetailPage>
 */
class VacancyResource extends ModelResource
{
    protected string $model = Vacancy::class;

    protected string $title = 'Вакансии';

    protected string $column = 'title';

    protected bool $withPolicy = true;

    protected function search(): array
    {
        return ['id', 'title'];
    }

    protected function modifyQueryBuilder(Builder $builder): Builder
    {
        return $builder->when(auth()->user()->isFilialManagerUser(), function (Builder $builder) {
            return $builder->whereHas('filial', fn(Builder $q) => $q->where('filials.manager_id', auth()->user()->getKey()));
        });
    }

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            VacancyIndexPage::class,
            VacancyFormPage::class,
            VacancyDetailPage::class,
        ];
    }
}
