<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Comment;

use App\Models\Question;
use App\Models\Result;
use App\MoonShine\Resources\Like\LikeResource;
use App\MoonShine\Resources\Question\QuestionResource;
use App\MoonShine\Resources\Result\ResultResource;
use App\Models\Comment;
use App\MoonShine\Resources\Comment\Pages\CommentIndexPage;
use App\MoonShine\Resources\Comment\Pages\CommentFormPage;
use App\MoonShine\Resources\Comment\Pages\CommentDetailPage;

use App\MoonShine\Resources\User\UserResource;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\MorphMany;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Textarea;

/**
 * @extends ModelResource<Comment, CommentIndexPage, CommentFormPage, CommentDetailPage>
 */
class CommentResource extends ModelResource
{
    protected string $model = Comment::class;

    protected string $title = 'Комментарии';

    protected bool $withPolicy = true;

    protected function search(): array
    {
        return ['id', 'content'];
    }

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
            CommentIndexPage::class,
            CommentFormPage::class,
            CommentDetailPage::class,
        ];
    }
}
