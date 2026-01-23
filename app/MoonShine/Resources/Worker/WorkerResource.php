<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Worker;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Worker;
use App\MoonShine\Resources\Worker\Pages\WorkerIndexPage;
use App\MoonShine\Resources\Worker\Pages\WorkerFormPage;
use App\MoonShine\Resources\Worker\Pages\WorkerDetailPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;

/**
 * @extends ModelResource<Worker, WorkerIndexPage, WorkerFormPage, WorkerDetailPage>
 */
class WorkerResource extends ModelResource
{
    protected string $model = Worker::class;

    protected string $title = 'Работники';

    protected string $column = 'name';

    protected bool $withPolicy = true;

    protected function search(): array
    {
        return ['id', 'name', 'surname'];
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
            WorkerIndexPage::class,
            WorkerFormPage::class,
            WorkerDetailPage::class,
        ];
    }
}
