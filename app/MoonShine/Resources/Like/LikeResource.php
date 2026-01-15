<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Like;

use App\Models\Result;
use App\MoonShine\Resources\Result\ResultResource;
use Illuminate\Database\Eloquent\Model;
use App\Models\Like;
use App\MoonShine\Resources\Like\Pages\LikeIndexPage;
use App\MoonShine\Resources\Like\Pages\LikeFormPage;
use App\MoonShine\Resources\Like\Pages\LikeDetailPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;

/**
 * @extends ModelResource<Like, LikeIndexPage, LikeFormPage, LikeDetailPage>
 */
class LikeResource extends ModelResource
{
    protected string $model = Like::class;

    protected string $title = 'Лайки';

    public array $morphResources = [
        Result::class => ResultResource::class,
    ];

    public array $morphTypes = [
        Result::class => ['id', 'Результат']
    ];

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            LikeIndexPage::class,
            LikeFormPage::class,
            LikeDetailPage::class,
        ];
    }
}
