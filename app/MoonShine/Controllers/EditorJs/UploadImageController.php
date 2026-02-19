<?php

declare(strict_types=1);

namespace App\MoonShine\Controllers\EditorJs;

use App\Adapters\ImageTransformer\ImageTransformerContract;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use MoonShine\Contracts\Core\DependencyInjection\CrudRequestContract;
use MoonShine\Crud\Contracts\Notifications\MoonShineNotificationContract;
use MoonShine\Laravel\Http\Controllers\MoonShineController;
use Symfony\Component\HttpFoundation\Response;

final class UploadImageController extends MoonShineController
{
    public function __construct(
        MoonShineNotificationContract $notification,
        private readonly ImageTransformerContract $imageTransformer,
    ) {
        parent::__construct($notification);
    }

    public function byFile(CrudRequestContract $request): Response
    {
        $request->validate([
            'image' => 'required|image|max:4000',
        ]);

        $image = $this->imageTransformer->image($request->file('image'))->scaleDown(width: 800)->quality(70)->get();

        $imagePath = Storage::putFileAs(
            path: 'uploads/'.Carbon::now()->format('Y-m'),
            file: $image,
            name: Str::random(50).'.'.$image->getClientOriginalExtension()
        );

        return response()->json(['url' => Storage::url($imagePath)]);
    }

    public function byUrl(CrudRequestContract $request): Response
    {
        $request->validate([
            'url' => 'required|url',
        ]);

        $url = $request->get('url');

        try {
            // Получаем содержимое изображения по URL
            $response = Http::get($url);
            if (! $response->ok()) {
                return response()->json(['error' => 'Невозможно загрузить изображение'], 400);
            }

            $content = $response->body();

            // Проверяем MIME-тип
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->buffer($content);

            $allowedTypes = [
                'image/png',
                'image/gif',
                'image/jpeg',
                'image/svg+xml',
                'image/webp',
            ];

            if (! in_array($mimeType, $allowedTypes)) {
                return response()->json(['success' => 0], 400);
            }

            // Создаём временный файл
            $tempPath = tempnam(sys_get_temp_dir(), 'img_');
            $extension = $this->mimeToExtension($mimeType);
            $tempFile = $tempPath.'.'.$extension;

            file_put_contents($tempFile, $content);

            $image = new UploadedFile(
                $tempFile,
                'image.'.$extension,
                $mimeType,
                filesize($tempFile),
                true
            );

            $image = $this->imageTransformer->image($image)->scaleDown(width: 800)->quality(70)->get();

            $imagePath = Storage::putFileAs(
                path: 'uploads/'.Carbon::now()->format('Y-m'),
                file: $image,
                name: Str::random(50).'.'.$image->getClientOriginalExtension()
            );

            // Удаляем временный файл
            unlink($tempFile);

            return response()->json(['url' => Storage::url($imagePath)]);

        } catch (\Exception $e) {
            return response()->json(['success' => 0], 400);
        }

    }

    public function remove(CrudRequestContract $request): Response
    {
        $request->validate([
            'urlFile' => 'required|url',
        ]);

        $urlFile = $request->get('urlFile');

        $path = substr($urlFile, strpos($urlFile, 'uploads/'));

        if (Storage::exists($path)) {
            Storage::delete($path);

            return response()->json(['success' => 1]);
        }

        return response()->json(['success' => 0]);
    }

    private function mimeToExtension($mime): string
    {
        $map = [
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/jpeg' => 'jpg',
            'image/svg+xml' => 'svg',
            'image/webp' => 'webp',
        ];

        return $map[$mime] ?? 'jpg';
    }
}
