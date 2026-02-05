<?php

declare(strict_types=1);

namespace App\Adapters\ImageTransformer;

use Illuminate\Http\UploadedFile;

interface ImageTransformerContract
{
    public function image(UploadedFile $image): self;

    public function scale(int|null $width = null, int|null $height = null): self;

    public function scaleDown(int|null $width = null, int|null $height = null): self;

    public function resize(int|null $width = null, int|null $height = null): self;

    public function resizeDown(int|null $width = null, int|null $height = null): self;

    public function quality(int $value): self;

    public function get(): UploadedFile;
}
