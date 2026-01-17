<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Result\Pages;

use App\MoonShine\Resources\Tag\TagResource;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use MoonShine\Laravel\Fields\Relationships\MorphToMany;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FormBuilderContract;
use MoonShine\UI\Components\Alert;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use App\MoonShine\Resources\Result\ResultResource;
use MoonShine\Support\ListOf;
use MoonShine\UI\Fields\Field;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Json;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Url;
use Throwable;


/**
 * @extends FormPage<ResultResource>
 */
class ResultFormPage extends FormPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                Alert::make()->content('Лучшая растановка фото: 1-ая ДО, 2-ая ПОСЛЕ, далее без разницы'),
                Image::make('Фото', 'images')
                    ->customName(fn(UploadedFile $file, Field $field) => "results/" . Carbon::now()->format('Y-m') . "/" . Str::random(50) . '.' . $file->extension())
                    ->multiple()
                    ->removable(),
                Text::make('Кол-во графтов', 'count_grafts'),
                Text::make('Кол-во мес-ев', 'count_months'),
                Text::make('Панч', 'panch'),
                Url::make('Видео', 'video_url'),
                MorphToMany::make('Теги', 'tags', resource: TagResource::class)->selectMode()->searchable()->creatable(),
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
            'images' => $item->getKey() === null ? ['required', 'array', 'min:2'] : ['nullable'],
            'images.*' => ['image', 'max:1024'],
            'count_grafts' => ['nullable', 'numeric', 'min:1', 'max:50000'],
            'count_months' => ['nullable', 'numeric', 'min:1', 'max:100'],
            'panch' => ['nullable', 'numeric'],
            'video_url' => ['nullable', 'url'],
            'tags' => ['nullable', 'array', 'max:3'],
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
