<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Post;

use App\Models\Post;
use App\MoonShine\Resources\Post\Pages\PostDetailPage;
use App\MoonShine\Resources\Post\Pages\PostFormPage;
use App\MoonShine\Resources\Post\Pages\PostIndexPage;
use Illuminate\Contracts\Database\Eloquent\Builder;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Laravel\Resources\ModelResource;

/**
 * @extends ModelResource<Post, PostIndexPage, PostFormPage, PostDetailPage>
 */
class PostResource extends ModelResource
{
    protected string $model = Post::class;

    protected string $title = 'Посты';

    protected bool $withPolicy = true;

    protected string $column = 'title';

    protected array $with = ['comments', 'likes', 'tags', 'filial', 'author', 'series'];

    protected function search(): array
    {
        return ['id', 'title'];
    }

    protected function modifyQueryBuilder(Builder $builder): Builder
    {
        return $builder->when(auth()->user()->isFilialManagerUser(), function (Builder $builder) {
            return $builder->whereHas('filial', fn (Builder $q) => $q->where('filials.manager_id', auth()->user()->getKey()));
        })->when(auth()->user()->isAuthorPostsUser(), function (Builder $builder) {
            return $builder->where('author_id', auth()->user()->getKey());
        });
    }

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            PostIndexPage::class,
            PostFormPage::class,
            PostDetailPage::class,
        ];
    }
}
