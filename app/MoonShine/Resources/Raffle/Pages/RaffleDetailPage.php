<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Raffle\Pages;

use App\MoonShine\Fields\Video;
use App\MoonShine\Resources\Comment\CommentResource;
use App\MoonShine\Resources\Like\LikeResource;
use App\MoonShine\Resources\Raffle\RaffleResource;
use App\MoonShine\Resources\Tag\TagResource;
use App\MoonShine\Resources\User\UserResource;
use Carbon\Carbon;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\MorphMany;
use MoonShine\Laravel\Fields\Relationships\MorphToMany;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\Support\AlpineJs;
use MoonShine\Support\Enums\HttpMethod;
use MoonShine\Support\Enums\JsEvent;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\ActionButton;
use MoonShine\UI\Components\Badge;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Link;
use MoonShine\UI\Components\Modal;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\UI\Components\Title;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;
use Throwable;

/**
 * @extends DetailPage<RaffleResource>
 */
class RaffleDetailPage extends DetailPage
{
    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make(),
            Image::make('Фото', 'image'),
            Video::make('Видео', 'video'),
            Text::make('Заголовок', 'title'),
            Textarea::make('Описание', 'description'),
            Text::make('Дата конца(Г.м.д)', 'date_end', fn ($item) => Carbon::parse($item->date_end)->format('Y.m.d')),
            BelongsTo::make('Победитель', 'winner', resource: UserResource::class),
            Date::make('Дата создания', 'created_at'),
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
            Box::make([
                Modal::make('', '', '', route('admin.raffles.get', $this->getItem()->id), [
                    Title::make(''),
                ])->name('winner-modal')->alwaysLoad(),
                ActionButton::make('Определить победителя', route('admin.raffles.select-winner', $this->getItem()->id))
                    ->icon('user')
                    ->primary()
                    ->async(
                        method: HttpMethod::POST,
                        events: [
                            AlpineJs::event(JsEvent::MODAL_TOGGLED, 'winner-modal'),
                        ]
                    ),
                ActionButton::make('Оповестить в ТГ канале', route('admin.raffles.send-messenger-channel', $this->getItem()->id))
                    ->icon('bell-alert')
                    ->primary()
                    ->async(),
            ]),
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
