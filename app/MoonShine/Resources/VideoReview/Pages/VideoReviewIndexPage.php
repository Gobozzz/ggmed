<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\VideoReview\Pages;

use App\MoonShine\Resources\Filial\FilialResource;
use App\MoonShine\Resources\Tag\TagResource;
use App\MoonShine\Resources\VideoReview\VideoReviewResource;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\MorphToMany;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\Laravel\QueryTags\QueryTag;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\Metrics\Wrapped\Metric;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\UI\Fields\File;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;
use Throwable;

/**
 * @extends IndexPage<VideoReviewResource>
 */
class VideoReviewIndexPage extends IndexPage
{
    protected bool $isLazy = true;

    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make(),
            Text::make('Комменты', 'comments', fn ($item) => (string) $item->comments->count() > 0 ? $item->comments->count() : 'Нет')->link(
                link: fn ($value, Text $ctx) => $this->getResource()->getDetailPageUrl($ctx->getData()->getKey()),
                icon: 'chat-bubble-left-right',
            ),
            Text::make('Лайки', 'likes', fn ($item) => $item->likes->count() > 0 ? $item->likes->count() : 'Нет')->link(
                link: fn ($value, Text $ctx) => $this->getResource()->getDetailPageUrl($ctx->getData()->getKey()),
                icon: 'heart',
            ),
            MorphToMany::make('Теги', 'tags', resource: TagResource::class)->onlyCount(),
            Image::make('Превью', 'preview'),
            File::make('Видео', 'video'),
            Text::make('Заголовок', 'title'),
            Textarea::make('Описание', 'content', fn ($item) => mb_substr($item->content ?? '', 0, 100, 'utf-8')),
            BelongsTo::make('Филиал', 'filial', resource: FilialResource::class),
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
