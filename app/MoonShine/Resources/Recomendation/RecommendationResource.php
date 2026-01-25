<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Recomendation;

use App\Models\Recommendation;
use App\MoonShine\Resources\Recomendation\Pages\RecommendationDetailPage;
use App\MoonShine\Resources\Recomendation\Pages\RecommendationFormPage;
use App\MoonShine\Resources\Recomendation\Pages\RecommendationIndexPage;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Laravel\Resources\ModelResource;

/**
 * @extends ModelResource<Recommendation, RecommendationIndexPage, RecommendationFormPage, RecommendationDetailPage>
 */
class RecommendationResource extends ModelResource
{
    protected string $model = Recommendation::class;

    protected string $title = 'Рекомендации';

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
            RecommendationIndexPage::class,
            RecommendationFormPage::class,
            RecommendationDetailPage::class,
        ];
    }
}
