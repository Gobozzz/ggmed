<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Like;

use App\Models\Question;
use App\Models\Result;
use App\MoonShine\Resources\Question\QuestionResource;
use App\MoonShine\Resources\Result\ResultResource;
use App\Models\Like;
use App\MoonShine\Resources\Like\Pages\LikeIndexPage;
use App\MoonShine\Resources\Like\Pages\LikeFormPage;
use App\MoonShine\Resources\Like\Pages\LikeDetailPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;

/**
 * @extends ModelResource<Like, LikeIndexPage, LikeFormPage, LikeDetailPage>
 */
class LikeResource extends ModelResource
{
    protected string $model = Like::class;

    protected string $title = 'Лайки';

    protected bool $withPolicy = true;

    public array $morphResources = [
        Result::class => ResultResource::class,
        Question::class => QuestionResource::class,
    ];

    public array $morphTypes = [
        Result::class => ['id', 'Результат'],
        Question::class => ['id', 'Вопрос'],
    ];

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            LikeIndexPage::class,
            LikeFormPage::class,
            LikeDetailPage::class,
        ];
    }
}
