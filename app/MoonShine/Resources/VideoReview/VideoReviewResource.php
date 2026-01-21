<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\VideoReview;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\VideoReview;
use App\MoonShine\Resources\VideoReview\Pages\VideoReviewIndexPage;
use App\MoonShine\Resources\VideoReview\Pages\VideoReviewFormPage;
use App\MoonShine\Resources\VideoReview\Pages\VideoReviewDetailPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;

/**
 * @extends ModelResource<VideoReview, VideoReviewIndexPage, VideoReviewFormPage, VideoReviewDetailPage>
 */
class VideoReviewResource extends ModelResource
{
    protected string $model = VideoReview::class;

    protected string $title = 'Видео отзывы';

    protected bool $withPolicy = true;

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
            VideoReviewIndexPage::class,
            VideoReviewFormPage::class,
            VideoReviewDetailPage::class,
        ];
    }
}
