<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Order\Pages;

use App\Enums\Payments\PaymentProvider;
use App\Enums\Payments\PaymentStatus;
use App\MoonShine\Resources\Order\OrderResource;
use Illuminate\Validation\Rules\Enum;
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
use MoonShine\UI\Fields\Email;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Phone;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;
use Throwable;

/**
 * @extends FormPage<OrderResource>
 */
class OrderFormPage extends FormPage
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
                    Tab::make('Плательщик', [
                        Text::make('Имя', 'customer_name'),
                        Phone::make('Телефон', 'customer_phone'),
                        Email::make('Почта', 'customer_email'),
                    ]),
                    Tab::make('Доставка', [
                        Text::make('Город', 'customer_city'),
                        Text::make('Улица', 'customer_street'),
                        Text::make('Номер дома', 'customer_house'),
                    ]),
                    Tab::make('Платеж', [
                        Select::make('Статус платежа', 'payment_status')->options(PaymentStatus::getAll()),
                    ])->canSee(fn () => $this->getItem()->payment_provider === PaymentProvider::CASH),
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
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:40'],
            'customer_name' => ['required', 'string', 'max:100'],
            'customer_city' => ['required', 'string', 'max:50'],
            'customer_street' => ['required', 'string', 'max:80'],
            'customer_house' => ['required', 'string', 'max:20'],
            'payment_status' => ['required', new Enum(PaymentStatus::class)],
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
