<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\OrderItem;

use App\Models\OrderItem;
use App\MoonShine\Resources\OrderItem\Pages\OrderItemDetailPage;
use App\MoonShine\Resources\OrderItem\Pages\OrderItemFormPage;
use App\MoonShine\Resources\OrderItem\Pages\OrderItemIndexPage;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Laravel\Resources\ModelResource;

/**
 * @extends ModelResource<OrderItem, OrderItemIndexPage, OrderItemFormPage, OrderItemDetailPage>
 */
class OrderItemResource extends ModelResource
{
    protected string $model = OrderItem::class;

    protected string $title = 'Позиции заказов';

    protected string $column = 'title';

    protected bool $withPolicy = true;

    protected array $with = ['product'];

    protected function search(): array
    {
        return ['title'];
    }

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            OrderItemIndexPage::class,
            OrderItemFormPage::class,
            OrderItemDetailPage::class,
        ];
    }
}
