<?php

declare(strict_types=1);

namespace App\MoonShine\Controllers\Post;

use App\Adapters\AiAssistant\AiAssistantContract;
use App\Adapters\ImageTransformer\ImageTransformerContract;
use App\DTO\AI\AiMessage;
use App\Enums\AI\AiMessageRole;
use App\Enums\LevelHipe;
use App\FakeGenerators\EditorGenerator;
use App\Models\Post;
use App\MoonShine\Converters\MarkdownToEditorJsConverter;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use MoonShine\Contracts\Core\DependencyInjection\CrudRequestContract;
use MoonShine\Crud\Contracts\Notifications\MoonShineNotificationContract;
use MoonShine\Laravel\Http\Controllers\MoonShineController;
use Symfony\Component\HttpFoundation\Response;

final class PostGenerateWithAIController extends MoonShineController
{
    public function __construct(
        MoonShineNotificationContract $notification,
        private readonly AiAssistantContract $aiAssistant,
        private readonly MarkdownToEditorJsConverter $mdToEditorJsConverter,
        private readonly ImageTransformerContract $imageTransformer,
    ) {
        parent::__construct($notification);
    }

    public function __invoke(CrudRequestContract $request): Response
    {
        Gate::authorize('ai-generate', Post::class);

        request()->validate([
            'theme' => ['required', 'string', 'max:500'],
            'image' => ['required', 'image', 'max:4000'],
        ], [
            'theme.required' => 'Тема обязательна',
            'theme.max' => 'Напиши не больше 500 символов',
            'image.required' => 'Фото обязательно',
            'image.max' => 'Фото должно быть не больше 1Мб',
        ]);

        $image = $this->imageTransformer->image($request->file('image'))->scaleDown(width: 1200)->quality(70)->get();

        $imagePath = Storage::putFileAs(
            path: 'posts/'.Carbon::now()->format('Y-m'),
            file: $image,
            name: Str::random(50).'.'.$image->getClientOriginalExtension()
        );

        $aiAnswer = $this->aiAssistant->sendRequest([
            new AiMessage(content: $this->getSystemPromptForEditor(), role: AiMessageRole::SYSTEM),
            new AiMessage(content: 'Тема статьи:'.request()->get('theme'), role: AiMessageRole::USER),
        ]);

        $content = $this->mdToEditorJsConverter->convert($aiAnswer->content);

        $aiAnswerSeo = $this->aiAssistant->sendRequest([
            new AiMessage(content: $this->getSystemPromptForSeo(), role: AiMessageRole::SYSTEM),
            new AiMessage(content: 'Тема статьи:'.request()->get('theme'), role: AiMessageRole::USER),
        ]);

        try {
            $seoData = json_decode($aiAnswerSeo->content, true);
            if ($seoData === null) {
                throw new \Exception;
            }
        } catch (\Exception $e) {
            return $this->json(message: 'AI не смогла сформировать SEO ядро. Попробуйте еще раз.');
        }

        $title = isset($seoData['title']) ? mb_substr($seoData['title'], 0, 100, 'utf8') : mb_substr($request->get('theme'), 0, 50, 'utf-8');
        $slug = Str::slug($title);

        $post = Post::create([
            'meta_title' => isset($seoData['meta_title']) ? mb_substr($seoData['meta_title'], 0, 100, 'utf8') : null,
            'meta_description' => isset($seoData['meta_description']) ? mb_substr($seoData['meta_description'], 0, 160, 'utf8') : null,
            'title' => $title,
            'description' => isset($seoData['description']) ? mb_substr($seoData['description'], 0, 255, 'utf8') : mb_substr($request->get('theme'), 0, 255, 'utf8'),
            'slug' => Post::query()->where('slug', $slug)->exists() ? ($slug.'-'.rand(2, 40)) : mb_substr($slug, 0, 200, 'utf8'),
            'image' => $imagePath,
            'content' => json_encode($content) ?? json_encode(EditorGenerator::make(1)),
            'time_to_read' => isset($aiData['time_to_read']) && (int) $aiData['time_to_read'] <= 50 ? $aiData['time_to_read'] : 9,
            'filial_id' => null,
            'author_id' => $this->auth()->id(),
            'level_hipe' => LevelHipe::LOW,
            'is_published' => false,
        ]);

        return $this->json(message: 'Статья создана', data: ['post' => $post]);
    }

    private function getSystemPromptForEditor(): string
    {
        return 'Ты отличный писатель статей! Твоя задача писать большие и подробные статьи в MD формате. Обязательно в MD формате.Любую тему ты раскрываешь очень подробно, все тонкости и рядом стоящие темы. Обычно ты пишешь статьи около 10000 символов и больше.';
    }

    private function getSystemPromptForSeo(): string
    {
        return '
        Ты отличный SEO разработчик. Твоя задача грамотно подбирать заголовки и описание для статей.
        Тебе будут дана тема статьи. Ты к ней даешь данные в виде json-объекта.
        Какие данные нужны:
        - meta_title - строка не более 90 символов
        - meta_description - строка не более 150 символов
        - title - строка не более 90 символов
        - description - строка не более 255 символов (это описание в карточке статьи)
        - time_to_read - время на чтение в минутах не более 50
        Формат твоего ответа:
        {
            поле: значение,
            и так далее
        }
        Никаких лишних символов сразу с скобочек начинаешь ответ, и заканчиваешь также, чтобы мне было легко парсить твой ответ.
        ';
    }
}
