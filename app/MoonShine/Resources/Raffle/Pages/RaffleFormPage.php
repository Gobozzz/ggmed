<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Raffle\Pages;

use App\MoonShine\Fields\CustomImage;
use App\MoonShine\Resources\Raffle\RaffleResource;
use App\MoonShine\Resources\User\UserResource;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\FormBuilderContract;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Slug;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\ActionButton;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Components\Tabs;
use MoonShine\UI\Components\Tabs\Tab;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\Field;
use MoonShine\UI\Fields\File;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;
use Sckatik\MoonshineEditorJs\Fields\EditorJs;
use Throwable;

/**
 * @extends FormPage<RaffleResource>
 */
class RaffleFormPage extends FormPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                ActionButton::make('Сохранить')->primary()->setAttribute('type', 'submit'),
                Tabs::make([
                    Tab::make('Основная информация', [
                        CustomImage::make('Фото (горизонтальное, необяз)', 'image')
                            ->scaleDown(width: 320)
                            ->quality(70)
                            ->removable()
                            ->customName(fn (UploadedFile $file, Field $field) => 'raffles/'.Carbon::now()->format('Y-m').'/'.Str::random(50).'.'.$file->extension()),
                        File::make('Запись розыгрыша (необяз, не более 20мб, горизонтальное)', 'video')
                            ->removable()
                            ->customName(fn (UploadedFile $file, Field $field) => 'raffles-videos/'.Carbon::now()->format('Y-m').'/'.Str::random(50).'.'.$file->extension()),
                        Text::make('Заголовок', 'title'),
                        Textarea::make('Описание', 'description'),
                        Date::make('Дата конца', 'date_end'),
                        BelongsTo::make('Победитель', 'winner', formatted: fn ($item) => $item->name.' ('.($item->phone ?? $item->email).')', resource: UserResource::class)
                            ->nullable()
                            ->asyncSearch(),
                    ]),
                    Tab::make('SEO', [
                        Slug::make('Слаг', 'slug')->from('title'),
                        Text::make('Meta Заголовок', 'meta_title'),
                        Textarea::make('Meta Описание', 'meta_description'),
                    ]),
                    Tab::make('Редактор', [
                        EditorJs::make('Редактор', 'content'),
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
            'video' => ['nullable', 'file', 'mimes:mp4', 'max:22000'],
            'title' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:200', 'unique:raffles,slug'.($item->getKey() === null ? '' : ','.$item->getKey())],
            'meta_title' => ['nullable', 'string', 'max:100'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'date_end' => ['required', 'date'],
            'winner_id' => ['nullable', 'integer', 'exists:users,id'],
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
