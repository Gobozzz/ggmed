<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Test;

use App\Models\Test;
use App\MoonShine\Resources\Test\Pages\TestDetailPage;
use App\MoonShine\Resources\Test\Pages\TestFormPage;
use App\MoonShine\Resources\Test\Pages\TestIndexPage;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Laravel\Resources\ModelResource;

/**
 * @extends ModelResource<Test, TestIndexPage, TestFormPage, TestDetailPage>
 */
class TestResource extends ModelResource
{
    protected string $model = Test::class;

    protected string $title = 'Тесты';

    protected string $column = 'title';

    protected array $with = ['tags', 'comments', 'likes'];

    protected bool $withPolicy = true;

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
            TestIndexPage::class,
            TestFormPage::class,
            TestDetailPage::class,
        ];
    }
}
