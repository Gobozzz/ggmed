<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Recomendation\Pages;

use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use MoonShine\Laravel\Fields\Slug;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FormBuilderContract;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use App\MoonShine\Resources\Recomendation\RecommendationResource;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\Tabs;
use MoonShine\UI\Components\Tabs\Tab;
use MoonShine\UI\Fields\Field;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;
use Sckatik\MoonshineEditorJs\Fields\EditorJs;
use Throwable;


/**
 * @extends FormPage<RecommendationResource>
 */
class RecommendationFormPage extends FormPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                Tabs::make([
                    Tab::make('Основная информация', [
                        Image::make('Фото', 'image')
                            ->customName(fn(UploadedFile $file, Field $field) => "recomendations/" . Carbon::now()->format('Y-m') . "/" . Str::random(50) . '.' . $file->extension()),
                        Text::make('Заголовок', 'title')->unescape(),
                        Slug::make('Слаг', 'slug')->from('title')->unescape(),
                        Textarea::make('Описание', 'description')->unescape(),
                        Text::make('Meta заголовок', 'meta_title')->unescape(),
                        Textarea::make('Meta описание', 'meta_description')->unescape(),
                    ]),
                    Tab::make('Редактор', [
                        EditorJs::make('Контент', 'content'),
                    ])
                ]),
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
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:500'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'content' => ['required'],
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
