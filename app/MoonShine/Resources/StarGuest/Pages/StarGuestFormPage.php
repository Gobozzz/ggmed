<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\StarGuest\Pages;

use App\MoonShine\Resources\StarGuest\StarGuestResource;
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
use MoonShine\UI\Fields\Json;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;
use MoonShine\UI\Fields\Url;
use Sckatik\MoonshineEditorJs\Fields\EditorJs;
use Throwable;

/**
 * @extends FormPage<StarGuestResource>
 */
class StarGuestFormPage extends FormPage
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
                            ->customName(fn (UploadedFile $file, Field $field) => 'stars-guests/'.Carbon::now()->format('Y-m').'/'.Str::random(50).'.'.$file->extension()),
                        Text::make('Имя', 'name')->unescape(),
                        Url::make('Видео', 'url')->unescape(),
                        Text::make('Слаг', 'slug')->unescape(),
                        Text::make('Meta Заголовок', 'meta_title')->unescape(),
                        Textarea::make('Meta Описание', 'meta_description')->unescape(),
                        Json::make('Пункты "Вкратце"', 'points')->onlyValue('Пункт', Text::make('Пункт')->unescape())->removable(),
                    ]),
                    Tab::make('Редактор', [
                        EditorJs::make('Редактор', 'content')->onApply(function ($item, $value) {
                            try {
                                if (count(json_decode($value, true)['blocks']) === 0) {
                                    $value = null;
                                }
                            } catch (Throwable $e) {
                                $value = null;
                            }
                            $item->content = $value;

                            return $item;
                        }),
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
            'image' => [$item->getKey() === null ? 'required' : 'nullable', 'image', 'max:1024'],
            'name' => ['required', 'string', 'max:100'],
            'url' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:200', 'unique:star_guests,slug'.($item->getKey() !== null ? ','.$item->getKey() : null)],
            'meta_title' => ['required', 'string', 'max:100'],
            'meta_description' => ['required', 'string', 'max:160'],
            'points' => ['required', 'array', 'min:1'],
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
