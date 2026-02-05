<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Announcement\Pages;

use App\Enums\LevelHipe;
use App\MoonShine\Fields\CustomImage;
use App\MoonShine\Resources\Announcement\AnnouncementResource;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Enum;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\FormBuilderContract;
use MoonShine\Laravel\Fields\Slug;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Tabs;
use MoonShine\UI\Components\Tabs\Tab;
use MoonShine\UI\Fields\Field;
use MoonShine\UI\Fields\File;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;
use Sckatik\MoonshineEditorJs\Fields\EditorJs;
use Throwable;

/**
 * @extends FormPage<AnnouncementResource>
 */
class AnnouncementFormPage extends FormPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            Box::make([
                Tabs::make([
                    Tab::make('Основные данные', [
                        ID::make(),
                        Select::make('Уровень продвижения', 'level_hipe')->options(LevelHipe::getAllLevelsHipe()),
                        CustomImage::make('Фото (вертикальное)', 'image')
                            ->scaleDown(width: 500)
                            ->quality(70)
                            ->customName(fn(UploadedFile $file, Field $field) => 'anons/' . Carbon::now()->format('Y-m') . '/' . Str::random(50) . '.' . $file->extension()),
                        File::make('Видео(необяз, не более 20мб)', 'video')
                            ->removable()
                            ->customName(fn(UploadedFile $file, Field $field) => 'anons-videos/' . Carbon::now()->format('Y-m') . '/' . Str::random(50) . '.' . $file->extension()),
                        Text::make('Заголовок', 'title')->unescape(),
                        Slug::make('Слаг', 'slug')->from('title')->unescape(),
                        Textarea::make('Описание', 'description')->unescape(),
                        Text::make('Meta Заголовок', 'meta_title')->unescape(),
                        Textarea::make('Meta Описание', 'meta_description')->unescape(),
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
            'level_hipe' => ['required', new Enum(LevelHipe::class)],
            'image' => [$item->getKey() === null ? 'required' : 'nullable', 'image', 'max:1024'],
            'video' => ['nullable', 'file', 'mimes:mp4', 'max:22000'],
            'title' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:200', 'unique:announcements,slug' . ($item->getKey() ? ',' . $item->getKey() : '')],
            'description' => ['required', 'string', 'max:255'],
            'meta_title' => ['nullable', 'string', 'max:100'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'content' => ['nullable'],
        ];
    }

    /**
     * @param FormBuilder $component
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
