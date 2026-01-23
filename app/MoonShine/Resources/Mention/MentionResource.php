<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Mention;

use Illuminate\Database\Eloquent\Model;
use App\Models\Mention;
use App\MoonShine\Resources\Mention\Pages\MentionIndexPage;
use App\MoonShine\Resources\Mention\Pages\MentionFormPage;
use App\MoonShine\Resources\Mention\Pages\MentionDetailPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;

/**
 * @extends ModelResource<Mention, MentionIndexPage, MentionFormPage, MentionDetailPage>
 */
class MentionResource extends ModelResource
{
    protected string $model = Mention::class;

    protected string $title = 'Упоминания';

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
            MentionIndexPage::class,
            MentionFormPage::class,
            MentionDetailPage::class,
        ];
    }
}
