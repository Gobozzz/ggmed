<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Post\Pages;

use App\Enums\LevelHipe;
use App\MoonShine\Resources\Filial\FilialResource;
use App\MoonShine\Resources\MoonShineUser\MoonShineUserResource;
use App\MoonShine\Resources\Post\PostResource;
use App\MoonShine\Resources\PostSeries\PostSeriesResource;
use App\MoonShine\Resources\Tag\TagResource;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\BelongsToMany;
use MoonShine\Laravel\Fields\Relationships\MorphToMany;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\Laravel\QueryTags\QueryTag;
use MoonShine\Support\AlpineJs;
use MoonShine\Support\Enums\Color;
use MoonShine\Support\Enums\JsEvent;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\ActionButton;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\UI\Components\Layout\LineBreak;
use MoonShine\UI\Components\Metrics\Wrapped\Metric;
use MoonShine\UI\Components\Modal;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;
use Throwable;

/**
 * @extends IndexPage<PostResource>
 */
class PostIndexPage extends IndexPage
{
    protected bool $isLazy = true;

    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make(),
            Image::make('Фото', 'image'),
            Text::make('Комменты', 'comments', fn($item) => (string)$item->comments->count() > 0 ? $item->comments->count() : 'Нет')->link(
                link: fn($value, Text $ctx) => $this->getResource()->getDetailPageUrl($ctx->getData()->getKey()),
                icon: 'chat-bubble-left-right',
            ),
            Text::make('Лайки', 'likes', fn($item) => $item->likes->count() > 0 ? $item->likes->count() : 'Нет')->link(
                link: fn($value, Text $ctx) => $this->getResource()->getDetailPageUrl($ctx->getData()->getKey()),
                icon: 'heart',
            ),
            MorphToMany::make('Теги', 'tags', resource: TagResource::class)->onlyCount(),
            Text::make('Заголовок', 'title')->prettyLimit(Color::PRIMARY),
            Number::make('Время на чтение', 'time_to_read', fn($item) => $item->time_to_read . ' мин.'),
            Date::make('Дата', 'created_at')->updateOnPreview(),
            BelongsTo::make('Филиал', 'filial', resource: FilialResource::class),
            BelongsTo::make('Автор', 'author', resource: MoonShineUserResource::class),
            Switcher::make('Опубликована?', 'is_published')->updateOnPreview(),
            Select::make('Продвижение', 'level_hipe')
                ->sortable()
                ->options(LevelHipe::getAllLevelsHipe())
                ->updateOnPreview()
                ->canSee(fn() => auth()->user()->isSuperUser()),
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
            Switcher::make('Опубликованные', 'is_published'),
            Select::make('Уровень продвижения', 'level_hipe')->options(LevelHipe::getAllLevelsHipe())->nullable(),
            Text::make('Заголовок', 'title'),
            Date::make('Дата', 'created_at'),
            BelongsTo::make('Филиал', 'filial', resource: FilialResource::class)->nullable(),
            BelongsTo::make('Автор', 'author', resource: MoonShineUserResource::class)->nullable(),
            BelongsToMany::make('Серии', 'series', resource: PostSeriesResource::class),
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
            ActionButton::make('Сгенерировать через AI')
                ->inModal(
                    title: 'Генерация статьи через искуственный интелект',
                    content: 'Укажите тему статьи',
                    name: 'post-generate-ai-modal',
                    builder: fn(Modal $modal, ActionButton $ctx) => $modal,
                    components: [
                        FormBuilder::make(fields: [
                            Textarea::make('Тема статьи (макс. 500 символов)', 'theme'),
                            Image::make('Фото (горизонтальное, не более 3мб)', 'image'),
                        ],
                        )->name('post-generate-ai-form')
                            ->async(
                                url: route('admin.posts.generate-ai'),
                                events: [
                                    AlpineJs::event(JsEvent::FORM_RESET, 'post-generate-ai-form'),
                                    AlpineJs::event(JsEvent::TABLE_UPDATED, $this->getListComponentName()),
                                ]
                            ),
                    ],
                )->icon('bolt')->canSee(fn() => auth()->user()->isSuperUser()),
            LineBreak::make(),
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
