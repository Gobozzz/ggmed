<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\User\Pages;

use App\Enums\UserStatus;
use App\MoonShine\Resources\User\UserResource;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Enum;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\FormBuilderContract;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\Support\ListOf;
use MoonShine\UI\Components\FormBuilder;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\Email;
use MoonShine\UI\Fields\Field;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Phone;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;
use Throwable;

/**
 * @extends FormPage<UserResource>
 */
class UserFormPage extends FormPage
{
    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function fields(): iterable
    {
        $statuses = [];
        foreach (UserStatus::cases() as $status) {
            $statuses[$status->value] = $status->label();
        }

        return [
            Box::make([
                ID::make(),
                Image::make('Аватар', 'avatar')
                    ->customName(fn (UploadedFile $file, Field $field) => 'users/'.Carbon::now()->format('Y-m').'/'.Str::random(50).'.'.$file->extension())
                    ->removable(),
                Text::make('Имя', 'name'),
                Phone::make('Телефон', 'phone'),
                Email::make('Почта', 'email'),
                Select::make('Статус', 'status')->options($statuses),
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
            'avatar' => ['nullable', 'image', 'max:1024'],
            'name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'.($item->getKey() !== null ? ",{$item->getKey()}" : '')],
            'phone' => ['nullable', 'string', 'max:40', 'unique:users,phone'.($item->getKey() !== null ? ",{$item->getKey()}" : '')],
            'status' => ['required', 'string', new Enum(UserStatus::class)],
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
