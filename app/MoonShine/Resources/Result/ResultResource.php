<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Result;

use Illuminate\Database\Eloquent\Model;
use App\Models\Result;
use App\MoonShine\Resources\Result\Pages\ResultIndexPage;
use App\MoonShine\Resources\Result\Pages\ResultFormPage;
use App\MoonShine\Resources\Result\Pages\ResultDetailPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;

/**
 * @extends ModelResource<Result, ResultIndexPage, ResultFormPage, ResultDetailPage>
 */
class ResultResource extends ModelResource
{
    protected string $model = Result::class;

    protected string $title = 'Результаты';

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
