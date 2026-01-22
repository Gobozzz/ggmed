<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\PostSeries;

use Illuminate\Database\Eloquent\Model;
use App\Models\PostSeries;
use App\MoonShine\Resources\PostSeries\Pages\PostSeriesIndexPage;
use App\MoonShine\Resources\PostSeries\Pages\PostSeriesFormPage;
use App\MoonShine\Resources\PostSeries\Pages\PostSeriesDetailPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;

/**
 * @extends ModelResource<PostSeries, PostSeriesIndexPage, PostSeriesFormPage, PostSeriesDetailPage>
 */
class PostSeriesResource extends ModelResource
{
    protected string $model = PostSeries::class;

    protected string $title = 'Серии';

    protected bool $withPolicy = true;

    protected string $column = 'title';

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
            PostSeriesIndexPage::class,
            PostSeriesFormPage::class,
            PostSeriesDetailPage::class,
        ];
    }
}
