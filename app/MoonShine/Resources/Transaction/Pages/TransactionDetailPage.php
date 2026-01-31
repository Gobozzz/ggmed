<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Transaction\Pages;

use App\MoonShine\Resources\Transaction\TransactionResource;
use App\MoonShine\Resources\User\UserResource;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;
use Throwable;

/**
 * @extends DetailPage<TransactionResource>
*/
class TransactionDetailPage extends DetailPage
{
    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make(),
            Text::make('Тип', 'type', fn($item) => $item->type->label()),
            Textarea::make('Комментарий', 'description'),
            Number::make('Сумма', 'amount'),
            BelongsTo::make('Пользователь', 'user', resource: UserResource::class),
            Textarea::make('Meta информация', 'metadata', fn($item) => json_encode($item->metadata) ?? "Отсутствует"),
        ];
    }

    protected function buttons(): ListOf
    {
        return parent::buttons();
    }

    /**
     * @param TableBuilder $component
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
