<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Raffle\Pages;

use App\MoonShine\Fields\Video;
use App\MoonShine\Resources\Raffle\RaffleResource;
use App\MoonShine\Resources\Tag\TagResource;
use App\MoonShine\Resources\User\UserResource;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\MorphToMany;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\Laravel\QueryTags\QueryTag;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\Metrics\Wrapped\Metric;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;
use Throwable;

/**
 * @extends IndexPage<RaffleResource>
 */
class RaffleIndexPage extends IndexPage
{
    protected bool $isLazy = true;

    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make(),
            Text::make('Комменты', 'comments', fn($item) => (string)$item->comments->count() > 0 ? $item->comments->count() : 'Нет')->link(
                link: fn($value, Text $ctx) => $this->getResource()->getDetailPageUrl($ctx->getData()->getKey()),
                icon: 'chat-bubble-left-right',
            ),
            Text::make('Лайки', 'likes', fn($item) => $item->likes->count() > 0 ? $item->likes->count() : 'Нет')->link(
                link: fn($value, Text $ctx) => $this->getResource()->getDetailPageUrl($ctx->getData()->getKey()),
                icon: 'heart',
            ),
            MorphToMany::make('Теги', 'tags', resource: TagResource::class)->onlyCount(),
            Image::make('Фото', 'image'),
            Video::make('Видео', 'video'),
            Textarea::make('Заголовок', 'title'),
            Date::make('Дата конца(Г.м.д)', 'date_end')->updateOnPreview()->sortable(),
            BelongsTo::make('Победитель', 'winner', resource: UserResource::class),
            Date::make('Дата создания', 'created_at')->updateOnPreview()->sortable(),
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
            Date::make('Дата создания', 'created_at'),
            Date::make('Дата конца', 'date_end'),
            Switcher::make('Без победителя', 'winner_id')
                ->onApply(fn(Builder $query) => $query->whereNull('winner_id')),
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
     * @param TableBuilder $component
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
