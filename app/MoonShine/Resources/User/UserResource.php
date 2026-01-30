<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\User;

use App\Enums\UserStatus;
use App\Models\User;
use App\MoonShine\Resources\User\Pages\UserDetailPage;
use App\MoonShine\Resources\User\Pages\UserFormPage;
use App\MoonShine\Resources\User\Pages\UserIndexPage;
use Illuminate\Support\Facades\DB;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use MoonShine\Laravel\Resources\ModelResource;

/**
 * @extends ModelResource<User, UserIndexPage, UserFormPage, UserDetailPage>
 */
class UserResource extends ModelResource
{
    protected string $model = User::class;

    protected string $title = 'Пользователи';

    protected string $column = 'name';

    protected bool $withPolicy = true;

    protected function search(): array
    {
        return ['id', 'name', 'email', 'phone'];
    }

    public function afterUpdated(DataWrapperContract $item): DataWrapperContract
    {
        if ($item->status === UserStatus::BLOCKED) {
            DB::table('sessions')
                ->where('user_id', $item->id)
                ->delete();
        }

        return $item;
    }

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            UserIndexPage::class,
            UserFormPage::class,
            UserDetailPage::class,
        ];
    }
}
