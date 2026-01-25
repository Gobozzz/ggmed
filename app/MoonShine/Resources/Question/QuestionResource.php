<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Question;

use App\Models\Question;
use App\MoonShine\Resources\Question\Pages\QuestionDetailPage;
use App\MoonShine\Resources\Question\Pages\QuestionFormPage;
use App\MoonShine\Resources\Question\Pages\QuestionIndexPage;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Laravel\Resources\ModelResource;

/**
 * @extends ModelResource<Question, QuestionIndexPage, QuestionFormPage, QuestionDetailPage>
 */
class QuestionResource extends ModelResource
{
    protected string $model = Question::class;

    protected string $title = 'Вопросы';

    protected bool $withPolicy = true;

    protected array $with = ['tags', 'comments', 'likes', 'user'];

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
            QuestionIndexPage::class,
            QuestionFormPage::class,
            QuestionDetailPage::class,
        ];
    }
}
