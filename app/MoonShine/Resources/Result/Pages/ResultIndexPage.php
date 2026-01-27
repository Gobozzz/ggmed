<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Result\Pages;

use App\Enums\LevelHipe;
use App\MoonShine\Resources\Result\ResultResource;
use App\MoonShine\Resources\Tag\TagResource;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Fields\Relationships\MorphToMany;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\Laravel\QueryTags\QueryTag;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\Metrics\Wrapped\Metric;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Url;
use Throwable;

/**
 * @extends IndexPage<ResultResource>
 */
class ResultIndexPage extends IndexPage
{
    protected bool $isLazy = true;

    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make(),
            Image::make('Фото', 'images')->multiple(),
            Text::make('Комменты', 'comments', fn ($item) => (string) $item->comments->count() > 0 ? $item->comments->count() : 'Нет')->link(
                link: fn ($value, Text $ctx) => $this->getResource()->getDetailPageUrl($ctx->getData()->getKey()),
                icon: 'chat-bubble-left-right',
            ),
            Text::make('Лайки', 'likes', fn ($item) => $item->likes->count() > 0 ? $item->likes->count() : 'Нет')->link(
                link: fn ($value, Text $ctx) => $this->getResource()->getDetailPageUrl($ctx->getData()->getKey()),
                icon: 'heart',
            ),
            MorphToMany::make('Теги', 'tags', resource: TagResource::class)->onlyCount(),
            Text::make('Кол-во графтов', 'count_grafts')->sortable(),
            Text::make('Кол-во мес-ев', 'count_months')->sortable(),
            Text::make('Панч', 'panch')->sortable(),
            Url::make('Видео', 'video_url')->blank(),
            Text::make('Продвижение', 'level_hipe', fn ($model) => $model->level_hipe->label())->sortable(),
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
            MorphToMany::make('Теги', 'tags', resource: TagResource::class)->selectMode()->asyncSearch(),
            Select::make('Уровень продвижения', 'level_hipe')->options(LevelHipe::getAllLevelsHipe())->nullable(),
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
