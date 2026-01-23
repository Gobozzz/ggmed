<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Filial\Pages;

use App\Models\MoonshineUser;
use App\MoonShine\Resources\MoonShineUser\MoonShineUserResource;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\Laravel\Fields\Slug;
use MoonShine\Laravel\Models\MoonshineUserRole;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FormBuilderContract;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use App\MoonShine\Resources\Filial\FilialResource;
use MoonShine\Support\ListOf;
use MoonShine\UI\Fields\Field;
use MoonShine\UI\Fields\File;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Textarea;
use Throwable;


/**
 * @extends FormPage<FilialResource>
 */
class FilialFormPage extends FormPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                Text::make('Название', 'name')->unescape(),
                Slug::make('Слаг', 'slug')->from('name')->unescape(),
                Text::make('Meta Заголовок', 'meta_title')->unescape(),
                Text::make('Meta Описание', 'meta_description')->unescape(),
                Image::make('Фото (не более 1мб, горизонтальное)', 'image')
                    ->customName(fn(UploadedFile $file, Field $field) => "filials/" . Carbon::now()->format('Y-m') . "/" . Str::random(50) . '.' . $file->extension()),
                File::make('Видео (не более 20мб, горизонтальное)', 'video')
                    ->customName(fn(UploadedFile $file, Field $field) => "filials-videos/" . Carbon::now()->format('Y-m') . "/" . Str::random(50) . '.' . $file->extension()),
                Text::make('Город', 'city')->unescape(),
                Text::make('Адрес', 'address')->unescape(),
                Text::make('Рабочее время', 'work_time')->unescape(),
                Number::make('Год основания', 'year'),
                Textarea::make('Код Яндекс карт', 'map_code')->unescape(),
                BelongsTo::make('Ответственный', 'manager', resource: MoonShineUserResource::class)
                    ->searchable()
                    ->nullable()
                    ->valuesQuery(static fn(Builder $q) => $q->where('moonshine_user_role_id', MoonshineUser::FILIAL_MANAGER_ROLE_ID)
                        ->select(['id', 'name']))->canSee(fn() => auth()->user()->isSuperUser()),
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

    public function prepareForValidation(): void
    {
        if (auth()->user()->isFilialManagerUser()) {
            request()->merge([
                'manager_id' => $this->getItem()->manager_id,
            ]);
        }
    }

    protected function rules(DataWrapperContract $item): array
    {
        return [
            "name" => ['required', 'string', 'max:255'],
            "slug" => ['nullable', 'string', 'max:255', 'unique:filials,slug' . ($item->getKey() === null ? '' : ',' . $item->getKey())],
            "meta_title" => ['required', 'string', 'max:255'],
            "meta_description" => ['required', 'string', 'max:500'],
            "image" => [$item->getKey() === null ? 'required' : 'nullable', 'image', 'max:1024'],
            "video" => ['nullable', 'file', 'mimes:mp4', 'max:22000'],
            "address" => ['required', 'string', 'max:255'],
            "city" => ['required', 'string', 'max:255'],
            "map_code" => ['required', 'string'],
            "work_time" => ['required', 'string', 'max:255'],
            "year" => ['required', 'numeric', 'min:2000', 'max:' . (int)date('Y') + 10],
            'manager_id' => ['nullable', 'integer', 'exists:moonshine_users,id'],
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
