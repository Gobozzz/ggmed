<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Product;

use App\Models\Product;
use App\MoonShine\Resources\Product\Pages\ProductDetailPage;
use App\MoonShine\Resources\Product\Pages\ProductFormPage;
use App\MoonShine\Resources\Product\Pages\ProductIndexPage;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Laravel\Resources\ModelResource;

/**
 * @extends ModelResource<Product, ProductIndexPage, ProductFormPage, ProductDetailPage>
 */
class ProductResource extends ModelResource
{
    protected string $model = Product::class;

    protected string $title = 'Товары';

    protected string $column = 'title';

    protected bool $withPolicy = true;

    protected array $with = ['tags', 'comments', 'likes'];

    protected function search(): array
    {
        return ['id', 'title', 'article'];
    }

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            ProductIndexPage::class,
            ProductFormPage::class,
            ProductDetailPage::class,
        ];
    }
}
