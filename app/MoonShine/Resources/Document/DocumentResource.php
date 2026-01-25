<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Document;

use App\Models\Document;
use App\MoonShine\Resources\Document\Pages\DocumentDetailPage;
use App\MoonShine\Resources\Document\Pages\DocumentFormPage;
use App\MoonShine\Resources\Document\Pages\DocumentIndexPage;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Laravel\Resources\ModelResource;

/**
 * @extends ModelResource<Document, DocumentIndexPage, DocumentFormPage, DocumentDetailPage>
 */
class DocumentResource extends ModelResource
{
    protected string $model = Document::class;

    protected string $title = 'Документы';

    protected string $column = 'title';

    protected bool $withPolicy = true;

    protected array $with = ['category'];

    protected function search(): array
    {
        return ['id', 'title'];
    }

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            DocumentIndexPage::class,
            DocumentFormPage::class,
            DocumentDetailPage::class,
        ];
    }
}
