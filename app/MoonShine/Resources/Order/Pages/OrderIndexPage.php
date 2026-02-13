<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Order\Pages;

use App\MoonShine\Resources\Order\OrderResource;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\Laravel\QueryTags\QueryTag;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\Metrics\Wrapped\Metric;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Phone;
use MoonShine\UI\Fields\Text;
use Throwable;

/**
 * @extends IndexPage<OrderResource>
 */
class OrderIndexPage extends IndexPage
{
    protected bool $isLazy = true;

    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make(),
            Number::make('Общая сумма заказа', 'total_amount', fn ($item) => $item->total_amount.', руб.')->sortable(),
            Text::make('Способ оплаты', 'payment_provider', fn ($item) => $item->payment_provider->label()),
            Text::make('Статус платежа', 'payment_status', fn ($item) => $item->payment_status->label()),
            Text::make('Имя покупателя', 'customer_name'),
            Phone::make('Телефон покупателя', 'customer_phone'),
            Text::make('Доставка', 'customer_city', fn ($item) => $item->customer_city.', '.$item->customer_street.', '.$item->customer_house),
            Date::make('Дата заказа', 'created_at')->sortable(),
        ];
    }

    /**
     * @return ListOf<ActionButtonContract>
     */
    protected function buttons(): ListOf
    {
        return parent::buttons();
    }

    /**
     * @return list<FieldContract>
     */
    protected function filters(): iterable
    {
        return [
            Date::make('Дата', 'created_at'),
            Text::make('Имя заказчика', 'customer_name'),
            Phone::make('Телефон заказчика', 'customer_phone'),
            Text::make('Почта', 'customer_email'),
        ];
    }

    /**
     * @return list<QueryTag>
     */
    protected function queryTags(): array
    {
        return [];
    }

    /**
     * @return list<Metric>
     */
    protected function metrics(): array
    {
        return [];
    }

    /**
     * @param  TableBuilder  $component
     * @return TableBuilder
     */
    protected function modifyListComponent(ComponentContract $component): ComponentContract
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
