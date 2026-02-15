<?php

declare(strict_types=1);

namespace App\MoonShine\Fields;

use App\Adapters\ImageTransformer\ImageTransformerContract;
use Illuminate\Http\UploadedFile;
use MoonShine\UI\Fields\Image;

class CustomImage extends Image
{
    const RESIZE_MODE_SCALE = 'scale';

    const RESIZE_MODE_SCALE_DOWN = 'scaleDown';

    const RESIZE_MODE_RESIZE = 'resize';

    const RESIZE_MODE_RESIZE_DOWN = 'resizeDown';

    private ?int $width = null;

    private ?int $height = null;

    private ?int $quality = null;

    private ?string $modeResize = null;

    public function scale(?int $width = null, ?int $height = null): self
    {
        $this->width = $width;
        $this->height = $height;
        $this->modeResize = self::RESIZE_MODE_SCALE;

        return $this;
    }

    public function scaleDown(?int $width = null, ?int $height = null): self
    {
        $this->width = $width;
        $this->height = $height;
        $this->modeResize = self::RESIZE_MODE_SCALE_DOWN;

        return $this;
    }

    public function resize(?int $width = null, ?int $height = null): self
    {
        $this->width = $width;
        $this->height = $height;
        $this->modeResize = self::RESIZE_MODE_RESIZE;

        return $this;
    }

    public function resizeDown(?int $width = null, ?int $height = null): self
    {
        $this->width = $width;
        $this->height = $height;
        $this->modeResize = self::RESIZE_MODE_RESIZE_DOWN;

        return $this;
    }

    public function quality(int $value): self
    {
        $this->quality = $value;

        return $this;
    }

    public function getRequestValue(int|string|null $index = null): mixed
    {
        $request_value = $this->prepareRequestValue(
            $this->getCore()->getRequest()->getFile(
                $this->getRequestNameDot($index),
            ) ?? false
        );
        $compress_image = null;
        if ($request_value) {
            if (is_array($request_value)) {
                $compress_image = [];
                foreach ($request_value as $image) {
                    $compress_image[] = $this->getCompressImage($image);
                }
            } else {
                $compress_image = $this->getCompressImage($request_value);
            }
        }

        return $compress_image ?? $request_value;
    }

    private function getCompressImage($image): UploadedFile
    {
        $imageTransformer = app(ImageTransformerContract::class);
        $compress_image = $imageTransformer->image($image);
        if ($this->modeResize !== null) {
            $this->applyResize($compress_image);
        }
        if ($this->quality !== null) {
            $compress_image->quality($this->quality);
        }

        return $compress_image->get();
    }

    private function applyResize(ImageTransformerContract &$image): void
    {
        switch ($this->modeResize) {
            case self::RESIZE_MODE_SCALE:
                $image->scale($this->width, $this->height);
                break;
            case self::RESIZE_MODE_SCALE_DOWN:
                $image->scaleDown($this->width, $this->height);
                break;
            case self::RESIZE_MODE_RESIZE:
                $image->resize($this->width, $this->height);
                break;
            case self::RESIZE_MODE_RESIZE_DOWN:
                $image->resizeDown($this->width, $this->height);
                break;
        }
    }
}
