<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Service\Pages;

use App\MoonShine\Resources\Filial\FilialResource;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\BelongsToMany;
use MoonShine\Laravel\Fields\Relationships\HasMany;
use MoonShine\Laravel\Fields\Slug;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\Contracts\UI\FieldContract;
use App\MoonShine\Resources\Service\ServiceResource;
use MoonShine\Support\ListOf;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use Throwable;

/**
 * @extends DetailPage<ServiceResource>
 */
class ServiceDetailPage extends DetailPage
{
    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make(),
            Image::make('Фото', 'image'),
            Text::make('Название', 'name'),
            Slug::make('Слаг', 'slug'),
            Text::make('Meta Заголовок', 'meta_title'),
            Text::make('Meta Описание', 'meta_description'),
            Text::make('Цена', 'price', fn($model) => ($model->is_start_price ? "от " : "") . number_format($model->price, 2, '.', ' ') . " ₽")->sortable(),
            BelongsTo::make('Родительская услуга', 'parent', resource: ServiceResource::class),
            HasMany::make('Подуслуги', 'children', resource: ServiceResource::class)->tabMode(),
            BelongsToMany::make('Инфомарция по филиалам', 'filials', resource: FilialResource::class)
                ->fields([
                    Text::make('Meta Заголовок', 'meta_title')->unescape(),
                    Text::make('Meta Описание', 'meta_description')->unescape(),
                    Number::make('Цена, ₽', 'price'),
                    Switcher::make('Начальная цена?', 'is_start_price'),
                ]),
        ];
    }

    protected function buttons(): ListOf
    {
        return parent::buttons();
    }

    /**
     * @param TableBuilder $component
     *
     * @return TableBuilder
     */
    protected function modifyDetailComponent(ComponentContract $component): ComponentContract
    {
        return $component;
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function topLayer(): array
    {
        return [
            ...parent::topLayer()
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function mainLayer(): array
    {
        return [
            ...parent::mainLayer()
        ];
    }

    /**
     * @return list<ComponentContract>
     * @throws Throwable
     */
    protected function bottomLayer(): array
    {
        return [
            ...parent::bottomLayer()
        ];
    }
}
