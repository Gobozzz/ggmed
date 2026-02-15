<?php

declare(strict_types=1);

namespace App\Adapters\ImageTransformer;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Encoders\AutoEncoder;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Laravel\Facades\Image;

final class InterventionImageTransformer implements ImageTransformerContract
{
    private ?ImageInterface $image = null;

    private ?int $width = null;

    private ?int $height = null;

    private ?int $quality = null;

    private ?string $modeResize = null;

    private ?string $originalFilename = null;

    private ?string $originalMimeType = null;

    public function image(UploadedFile $image): self
    {
        $this->image = Image::read($image);
        $this->originalFilename = $image->getClientOriginalName();
        $this->originalMimeType = $image->getClientMimeType();

        return $this;
    }

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

    public function get(): UploadedFile
    {
        if ($this->image === null) {
            throw new \Exception('Image is missing.');
        }
        if ($this->modeResize !== null) {
            $this->applyResize();
        }

        return $this->transformToUploadedFile();
    }

    private function transformToUploadedFile(): UploadedFile
    {
        $encoder = $this->quality === null ? new AutoEncoder : new AutoEncoder(quality: $this->quality);
        $imageData = $this->image->encode($encoder)->toString();

        $tempFilePath = tempnam(sys_get_temp_dir(), 'img_');
        file_put_contents($tempFilePath, $imageData);

        $mimeType = $this->originalMimeType ?? $this->image->origin()->mediaType();

        $filename = $this->originalFilename ?? 'transformed_image.jpg';

        return new UploadedFile(
            $tempFilePath,
            $filename,
            $mimeType,
            null,
            true
        );
    }

    private function applyResize(): void
    {
        $this->image = match ($this->modeResize) {
            self::RESIZE_MODE_RESIZE => $this->image->resize($this->width, $this->height),
            self::RESIZE_MODE_RESIZE_DOWN => $this->image->resizeDown($this->width, $this->height),
            self::RESIZE_MODE_SCALE => $this->image->scale($this->width, $this->height),
            self::RESIZE_MODE_SCALE_DOWN => $this->image->scaleDown($this->width, $this->height),
        };
    }
}
