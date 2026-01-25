<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\DocumentCategory;

use App\Models\DocumentCategory;
use App\MoonShine\Resources\DocumentCategory\Pages\DocumentCategoryDetailPage;
use App\MoonShine\Resources\DocumentCategory\Pages\DocumentCategoryFormPage;
use App\MoonShine\Resources\DocumentCategory\Pages\DocumentCategoryIndexPage;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Laravel\Resources\ModelResource;

/**
 * @extends ModelResource<DocumentCategory, DocumentCategoryIndexPage, DocumentCategoryFormPage, DocumentCategoryDetailPage>
 */
class DocumentCategoryResource extends ModelResource
{
    protected string $model = DocumentCategory::class;

    protected string $title = 'Категории';

    protected string $column = 'name';

    protected bool $withPolicy = true;

    protected array $with = ['documents'];

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
            DocumentCategoryIndexPage::class,
            DocumentCategoryFormPage::class,
            DocumentCategoryDetailPage::class,
        ];
    }
}
