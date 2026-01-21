<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Service\Pages;

use App\MoonShine\Resources\Filial\FilialResource;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Relationships\BelongsToMany;
use MoonShine\Laravel\Fields\Slug;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FormBuilderContract;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use App\MoonShine\Resources\Service\ServiceResource;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\Tabs;
use MoonShine\UI\Components\Tabs\Tab;
use MoonShine\UI\Fields\Field;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;
use Sckatik\MoonshineEditorJs\Fields\EditorJs;
use Throwable;


/**
 * @extends FormPage<ServiceResource>
 */
class ServiceFormPage extends FormPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            Box::make([
                Tabs::make(
                    [
                        Tab::make("Основные данные", [
                            ID::make(),
                            Image::make('Фото', 'image')
                                ->customName(fn(UploadedFile $file, Field $field) => "services/" . Carbon::now()->format('Y-m') . "/" . Str::random(50) . '.' . $file->extension()),
                            Text::make('Название', 'name')->unescape(),
                            Slug::make('Слаг', 'slug')->from('name'),
                            Text::make('Meta Заголовок', 'meta_title')->unescape(),
                            Text::make('Meta Описание', 'meta_description')->unescape(),
                            Number::make('Цена, ₽', 'price'),
                            Switcher::make('Начальная цена?', 'is_start_price'),
                            Textarea::make('Описание (В карточке)', 'description')->unescape(),
                            BelongsTo::make('Родительская услуга', 'parent', resource: ServiceResource::class)->searchable()->nullable(),
                        ]),
                        Tab::make('Редактор', [
                            EditorJs::make('Редактор', 'content'),
                        ]),
                        Tab::make(fn() => "Для филиалов (указано для " . ($this->getItem()?->filials()->count() ?? 0) . ")", [
                            BelongsToMany::make('Инфомарция по филиалам', 'filials', resource: FilialResource::class)
                                ->fields([
                                    Text::make('Meta Заголовок', 'meta_title')->unescape(),
                                    Text::make('Meta Описание', 'meta_description')->unescape(),
                                    Number::make('Цена, ₽', 'price'),
                                    Switcher::make('Начальная цена?', 'is_start_price'),
                                ]),
                        ]),
                    ]
                ),
            ]),
        ];
    }

    protected function buttons(): ListOf
    {
        return parent::buttons();
    }

    protected function formButtons(): ListOf
    {
        return parent::formButtons();
    }

    protected function rules(DataWrapperContract $item): array
    {
        return [
            'image' => [$item->getKey() === null ? 'required' : 'nullable', 'image', 'max:1024'],
            "name" => ['required', 'string', 'max:255'],
            "meta_title" => ['required', 'string', 'max:255'],
            "meta_description" => ['required', 'string', 'max:500'],
            "slug" => ['nullable', 'string', 'max:255', 'unique:services,slug' . ($item->getKey() ? "," . $item->getKey() : "")],
            "price" => ['required', 'numeric', 'min:1'],
            "is_start_price" => ['required', 'boolean:'],
            "description" => ['required', 'string', 'max:255'],
            "parent_id" => ['nullable', 'numeric', 'exists:services,id'],
            "content" => ['required'],
            "filials_pivot" => ['nullable', 'array'],
            "filials_pivot.*.pivot.price" => ['nullable', 'numeric', 'min:1'],
        ];
    }

    /**
     * @param FormBuilder $component
     *
     * @return FormBuilder
     */
    protected function modifyFormComponent(FormBuilderContract $component): FormBuilderContract
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
