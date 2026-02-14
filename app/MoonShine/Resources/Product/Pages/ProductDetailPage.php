<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Product\Pages;

use App\MoonShine\Resources\Comment\CommentResource;
use App\MoonShine\Resources\Like\LikeResource;
use App\MoonShine\Resources\Product\ProductResource;
use App\MoonShine\Resources\Tag\TagResource;
use App\MoonShine\Resources\User\UserResource;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\MorphMany;
use MoonShine\Laravel\Fields\Relationships\MorphToMany;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\Badge;
use MoonShine\UI\Components\Link;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;
use Throwable;

/**
 * @extends DetailPage<ProductResource>
 */
class ProductDetailPage extends DetailPage
{
    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make(),
            Text::make('Продвижение', 'level_hipe', fn ($model) => $model->level_hipe->label()),
            Image::make('Фото', 'images')->multiple(),
            Text::make('Арт.', 'article'),
            Text::make('Название', 'title'),
            Text::make('Слаг', 'slug'),
            Textarea::make('Описание', 'description'),
            Number::make('Цена', 'price', fn ($item) => $item->price.', руб'),
            Number::make('Старая цена', 'old_price', fn ($item) => $item->old_price ? ($item->old_price.', руб') : ''),
            Number::make('Цена в GG COIN', 'price_coin', fn ($item) => $item->price_coin ? ($item->price_coin.', GG COIN') : ''),
            Switcher::make('Можно купить только за GG COIN?', 'can_buy_only_coin'),
            Switcher::make('В наличии?', 'is_have'),
            Text::make('Бренд', 'brand'),
            Text::make('Состав', 'structure'),
            Text::make('Meta Заголовок', 'meta_title'),
            Textarea::make('Meta Описание', 'meta_description'),
            MorphToMany::make('Теги', 'tags', resource: TagResource::class)
                ->inLine(
                    separator: ' ',
                    badge: fn ($model, $value) => Badge::make((string) $value, 'primary'),
                    link: fn ($property, $value, $field): string|Link => Link::make(
                        app(TagResource::class)->getDetailPageUrl($property->id),
                        $value
                    )
                ),
            MorphMany::make('Комментарии 💬', 'comments', resource: CommentResource::class)
                ->fields([
                    ID::make(),
                    Textarea::make('Текст', 'content', fn ($item) => mb_substr($item->content, 0, 100, 'utf-8')),
                    BelongsTo::make('Пользователь', 'user', resource: UserResource::class),
                    Date::make('Дата', 'created_at'),
                ])->tabMode(),
            MorphMany::make('Лайки ❤️', 'likes', resource: LikeResource::class)
                ->fields([
                    ID::make(),
                    BelongsTo::make('Пользователь', 'user', resource: UserResource::class),
                    Date::make('Дата', 'created_at'),
                ])->tabMode()->searchable(),
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
