<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Result;

use App\Models\Result;
use App\MoonShine\Resources\Result\Pages\ResultDetailPage;
use App\MoonShine\Resources\Result\Pages\ResultFormPage;
use App\MoonShine\Resources\Result\Pages\ResultIndexPage;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Laravel\Resources\ModelResource;

/**
 * @extends ModelResource<Result, ResultIndexPage, ResultFormPage, ResultDetailPage>
 */
class ResultResource extends ModelResource
{
    protected string $model = Result::class;

    protected string $title = 'Результаты';

    protected bool $withPolicy = true;

    protected array $with = ['tags', 'comments', 'likes'];

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            ResultIndexPage::class,
            ResultFormPage::class,
            ResultDetailPage::class,
        ];
    }
}
