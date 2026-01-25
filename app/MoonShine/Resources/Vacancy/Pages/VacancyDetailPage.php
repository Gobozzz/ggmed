<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Vacancy\Pages;

use App\MoonShine\Resources\Filial\FilialResource;
use App\MoonShine\Resources\MoonShineUser\MoonShineUserResource;
use MoonShine\EasyMde\Fields\Markdown;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Components\Table\TableBuilder;
use MoonShine\Contracts\UI\FieldContract;
use App\MoonShine\Resources\Vacancy\VacancyResource;
use MoonShine\Support\ListOf;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Url;
use Throwable;


/**
 * @extends DetailPage<VacancyResource>
 */
class VacancyDetailPage extends DetailPage
{
    /**
     * @return list<FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            ID::make(),
            Text::make('Заголовок', 'title'),
            Text::make('ЗП', 'salary', fn($item) => $item->salary ? $item->salary . " " . $item->valute . " " . $item->what_pay : "Не указана"),
            Text::make('Ответственный', 'responsible'),
            Text::make('Адрес', 'address'),
            Url::make('Ссылка на внешний ист.', 'url', fn($item) => $item->url ?? 'Не указана')->blank(),
            Markdown::make('Описание(необяз)', 'content')->previewMode(),
            BelongsTo::make('Автор', 'author', resource: MoonShineUserResource::class),
            BelongsTo::make('Филиал', 'filial', resource: FilialResource::class),
            Date::make('Дата создания', 'created_at'),
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
