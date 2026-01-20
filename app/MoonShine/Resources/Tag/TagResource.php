<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Tag;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tag;
use App\MoonShine\Resources\Tag\Pages\TagIndexPage;
use App\MoonShine\Resources\Tag\Pages\TagFormPage;
use App\MoonShine\Resources\Tag\Pages\TagDetailPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;

/**
 * @extends ModelResource<Tag, TagIndexPage, TagFormPage, TagDetailPage>
 */
class TagResource extends ModelResource
{
    protected string $model = Tag::class;

    protected string $title = 'Теги';

    protected string $column = 'name';
    protected bool $withPolicy = true;

    protected function search(): array
    {
        return ['id', 'name', 'slug'];
    }

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            TagIndexPage::class,
            TagFormPage::class,
            TagDetailPage::class,
        ];
    }
}
