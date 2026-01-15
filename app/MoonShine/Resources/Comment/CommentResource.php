<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Comment;

use App\Models\Result;
use App\MoonShine\Resources\Result\ResultResource;
use Illuminate\Database\Eloquent\Model;
use App\Models\Comment;
use App\MoonShine\Resources\Comment\Pages\CommentIndexPage;
use App\MoonShine\Resources\Comment\Pages\CommentFormPage;
use App\MoonShine\Resources\Comment\Pages\CommentDetailPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;

/**
 * @extends ModelResource<Comment, CommentIndexPage, CommentFormPage, CommentDetailPage>
 */
class CommentResource extends ModelResource
{
    protected string $model = Comment::class;

    protected string $title = 'Комментарии';

    public array $morphResources = [
        Result::class => ResultResource::class,
    ];

    public array $morphTypes = [
        Result::class => ['id', 'Результат']
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
