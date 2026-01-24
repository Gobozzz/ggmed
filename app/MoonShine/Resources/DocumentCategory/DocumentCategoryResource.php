<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\DocumentCategory;

use Illuminate\Database\Eloquent\Model;
use App\Models\DocumentCategory;
use App\MoonShine\Resources\DocumentCategory\Pages\DocumentCategoryIndexPage;
use App\MoonShine\Resources\DocumentCategory\Pages\DocumentCategoryFormPage;
use App\MoonShine\Resources\DocumentCategory\Pages\DocumentCategoryDetailPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;

/**
 * @extends ModelResource<DocumentCategory, DocumentCategoryIndexPage, DocumentCategoryFormPage, DocumentCategoryDetailPage>
 */
class DocumentCategoryResource extends ModelResource
{
    protected string $model = DocumentCategory::class;

    protected string $title = 'Категории';

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
            DocumentCategoryIndexPage::class,
            DocumentCategoryFormPage::class,
            DocumentCategoryDetailPage::class,
        ];
    }
}
