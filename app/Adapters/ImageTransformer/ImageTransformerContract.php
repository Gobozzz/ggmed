<?php

declare(strict_types=1);

namespace App\Adapters\ImageTransformer;

use Illuminate\Http\UploadedFile;

interface ImageTransformerContract
{
    public function image(UploadedFile $image): self;

    public function scale(?int $width = null, ?int $height = null): self;

    public function scaleDown(?int $width = null, ?int $height = null): self;

    public function resize(?int $width = null, ?int $height = null): self;

    public function resizeDown(?int $width = null, ?int $height = null): self;

    public function quality(int $value): self;

    public function get(): UploadedFile;
}
