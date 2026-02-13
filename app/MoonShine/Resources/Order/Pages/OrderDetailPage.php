<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Order\Pages;

use App\MoonShine\Resources\Order\OrderResource;
use App\MoonShine\Resources\OrderItem\OrderItemResource;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Fields\Relationships\HasMany;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\Email;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Phone;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;
use Throwable;

/**
 * @extends DetailPage<OrderResource>
 */
class OrderDetailPage extends DetailPage
{
    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make(),
            Number::make('Сумма', 'total_amount', fn ($item) => $item->total_amount.', руб.'),
            Text::make('Способ оплаты', 'payment_provider', fn ($item) => $item->payment_provider->label()),
            Text::make('Статус платежа', 'payment_status', fn ($item) => $item->payment_status->label()),
            Number::make('Кол-во позиций', '', fn ($item) => $item->items()->count()),
            Text::make('Имя', 'customer_name'),
            Phone::make('Телефон', 'customer_phone'),
            Email::make('Почта', 'customer_email'),
            Text::make('Доставка', 'customer_city', fn ($item) => $item->customer_city.', '.$item->customer_street.', '.$item->customer_house),
            Date::make('Дата заказа', 'created_at'),
            Textarea::make('Комментарий заказчика', 'comment'),
            HasMany::make('Товары', 'items', resource: OrderItemResource::class)->tabMode(),
        ];
    }

    protected function buttons(): ListOf
    {
        return parent::buttons();
    }

    /**
     * @param  TableBuilder  $component
     * @return TableBuilder
     */
    protected function modifyDetailComponent(ComponentContract $component): ComponentContract
    {
        return $component;
    }

    /**
     * @return list<ComponentContract>
     *
     * @throws Throwable
     */
    protected function topLayer(): array
    {
        return [
            ...parent::topLayer(),
        ];
    }

    /**
     * @return list<ComponentContract>
     *
     * @throws Throwable
     */
    protected function mainLayer(): array
    {
        return [
            ...parent::mainLayer(),
        ];
    }

    /**
     * @return list<ComponentContract>
     *
     * @throws Throwable
     */
    protected function bottomLayer(): array
    {
        return [
            ...parent::bottomLayer(),
        ];
    }
}
