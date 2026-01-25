<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Announcement;

use App\Models\Announcement;
use App\MoonShine\Resources\Announcement\Pages\AnnouncementDetailPage;
use App\MoonShine\Resources\Announcement\Pages\AnnouncementFormPage;
use App\MoonShine\Resources\Announcement\Pages\AnnouncementIndexPage;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Laravel\Resources\ModelResource;

/**
 * @extends ModelResource<Announcement, AnnouncementIndexPage, AnnouncementFormPage, AnnouncementDetailPage>
 */
class AnnouncementResource extends ModelResource
{
    protected string $model = Announcement::class;

    protected string $title = 'Анонсы';

    protected string $column = 'title';

    protected bool $withPolicy = true;

    protected function search(): array
    {
        return ['id', 'title'];
    }

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            AnnouncementIndexPage::class,
            AnnouncementFormPage::class,
            AnnouncementDetailPage::class,
        ];
    }
}
