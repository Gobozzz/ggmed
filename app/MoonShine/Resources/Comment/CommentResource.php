<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Comment;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Product;
use App\Models\Question;
use App\Models\Result;
use App\Models\Test;
use App\Models\VideoReview;
use App\MoonShine\Resources\Comment\Pages\CommentDetailPage;
use App\MoonShine\Resources\Comment\Pages\CommentFormPage;
use App\MoonShine\Resources\Comment\Pages\CommentIndexPage;
use App\MoonShine\Resources\Post\PostResource;
use App\MoonShine\Resources\Product\ProductResource;
use App\MoonShine\Resources\Question\QuestionResource;
use App\MoonShine\Resources\Result\ResultResource;
use App\MoonShine\Resources\Test\TestResource;
use App\MoonShine\Resources\VideoReview\VideoReviewResource;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Laravel\Resources\ModelResource;

/**
 * @extends ModelResource<Comment, CommentIndexPage, CommentFormPage, CommentDetailPage>
 */
class CommentResource extends ModelResource
{
    protected string $model = Comment::class;

    protected string $title = 'Комментарии';

    protected bool $withPolicy = true;

    protected array $with = ['commentable', 'user'];

    protected function search(): array
    {
        return ['id', 'content'];
    }

    public array $morphResources = [
        Result::class => ResultResource::class,
        Question::class => QuestionResource::class,
        VideoReview::class => VideoReviewResource::class,
        Post::class => PostResource::class,
        Product::class => ProductResource::class,
        Test::class => TestResource::class,
    ];

    public array $morphTypes = [
        Result::class => ['id', 'Результат'],
        Question::class => ['id', 'Вопрос'],
        VideoReview::class => ['id', 'Видео отзыв'],
        Post::class => ['id', 'Пост'],
        Product::class => ['title', 'Товар'],
        Test::class => ['title', 'Тест'],
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
