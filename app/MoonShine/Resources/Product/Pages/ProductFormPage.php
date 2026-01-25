<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Product\Pages;

use App\MoonShine\Resources\Product\ProductResource;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\FormBuilderContract;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Tabs;
use MoonShine\UI\Components\Tabs\Tab;
use MoonShine\UI\Fields\Field;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;
use Sckatik\MoonshineEditorJs\Fields\EditorJs;
use Throwable;

/**
 * @extends FormPage<ProductResource>
 */
class ProductFormPage extends FormPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            Box::make([
                Tabs::make([
                    Tab::make('Основная информация', [
                        ID::make(),
                        Image::make('Фото', 'images')
                            ->customName(fn (UploadedFile $file, Field $field) => 'products/'.Carbon::now()->format('Y-m').'/'.Str::random(50).'.'.$file->extension())
                            ->multiple()
                            ->removable(),
                        Text::make('Арт.(необяз)', 'article')->unescape(),
                        Text::make('Название', 'title')->unescape(),
                        Textarea::make('Короткое описание', 'description')->unescape(),
                        Number::make('Цена', 'price', fn ($item) => $item->price.', руб')->step(0.01),
                        Number::make('Старая цена', 'old_price', fn ($item) => $item->old_price.', руб')->step(0.01),
                        Switcher::make('В наличии?', 'is_have'),
                        Text::make('Бренд', 'brand')->unescape(),
                        Text::make('Состав', 'structure')->unescape(),
                        Text::make('Meta Заголовок', 'meta_title')->unescape(),
                        Textarea::make('Meta Описание', 'meta_description')->unescape(),
                    ]),
                    Tab::make('Описание', [
                        EditorJs::make('Описание', 'content'),
                    ]),
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
            'images' => $item->getKey() === null ? ['required', 'array', 'min:1'] : ['nullable'],
            'images.*' => ['image', 'max:1024'],
            'title' => ['required', 'string', 'max:100'],
            'article' => ['nullable', 'string', 'max:50', 'unique:products,article'.($item->getKey() !== null ? ','.$item->getKey() : '')],
            'description' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:1'],
            'old_price' => ['nullable', 'numeric', 'min:1'],
            'is_have' => ['required', 'boolean'],
            'brand' => ['nullable', 'string', 'max:50'],
            'structure' => ['nullable', 'string', 'max:100'],
            'meta_title' => ['nullable', 'string', 'max:100'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'content' => ['nullable'],
        ];
    }

    /**
     * @param  FormBuilder  $component
     * @return FormBuilder
     */
    protected function modifyFormComponent(FormBuilderContract $component): FormBuilderContract
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
