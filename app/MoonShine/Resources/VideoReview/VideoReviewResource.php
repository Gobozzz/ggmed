<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\VideoReview;

use App\Models\VideoReview;
use App\MoonShine\Resources\VideoReview\Pages\VideoReviewDetailPage;
use App\MoonShine\Resources\VideoReview\Pages\VideoReviewFormPage;
use App\MoonShine\Resources\VideoReview\Pages\VideoReviewIndexPage;
use Illuminate\Contracts\Database\Eloquent\Builder;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Laravel\Resources\ModelResource;

/**
 * @extends ModelResource<VideoReview, VideoReviewIndexPage, VideoReviewFormPage, VideoReviewDetailPage>
 */
class VideoReviewResource extends ModelResource
{
    protected string $model = VideoReview::class;

    protected string $title = 'Видео отзывы';

    protected bool $withPolicy = true;

    protected array $with = ['filial', 'tags', 'comments', 'likes'];

    protected function modifyQueryBuilder(Builder $builder): Builder
    {
        return $builder->when(auth()->user()->isFilialManagerUser(), function (Builder $builder) {
            return $builder->whereHas('filial', fn (Builder $q) => $q->where('filials.manager_id', auth()->user()->getKey()));
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
