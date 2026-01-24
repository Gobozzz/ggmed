<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Product\Pages;

use App\MoonShine\Resources\Tag\TagResource;
use MoonShine\Laravel\Fields\Relationships\MorphToMany;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\QueryTags\QueryTag;
use MoonShine\UI\Components\Metrics\Wrapped\Metric;
use MoonShine\UI\Fields\ID;
use App\MoonShine\Resources\Product\ProductResource;
use MoonShine\Support\ListOf;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use Throwable;


/**
 * @extends IndexPage<ProductResource>
 */
class ProductIndexPage extends IndexPage
{
    protected bool $isLazy = true;

    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make(),
            Text::make('Комменты', 'comments', fn($item) => (string)$item->comments->count() > 0 ? $item->comments->count() : "Нет")->link(
                link: fn($value, Text $ctx) => $this->getResource()->getDetailPageUrl($ctx->getData()->getKey()),
                icon: "chat-bubble-left-right",
            ),
            Text::make('Лайки', 'likes', fn($item) => $item->likes->count() > 0 ? $item->likes->count() : "Нет")->link(
                link: fn($value, Text $ctx) => $this->getResource()->getDetailPageUrl($ctx->getData()->getKey()),
                icon: "heart",
            ),
            MorphToMany::make('Теги', 'tags', resource: TagResource::class)->onlyCount(),
            Image::make('Фото', 'images')->multiple(),
            Text::make('Арт.', 'article'),
            Text::make('Название', 'title'),
            Number::make('Цена', 'price', fn($item) => $item->price . ", руб"),
            Number::make('Старая цена', 'old_price', fn($item) => $item->old_price ? ($item->old_price . ", руб") : ''),
            Switcher::make('В наличии?', 'is_have'),
            Text::make('Бренд', 'brand'),
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
        return [];
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
     * @param TableBuilder $component
     *
     * @return TableBuilder
     */
    protected function modifyListComponent(ComponentContract $component): ComponentContract
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
