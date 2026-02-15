<?php

declare(strict_types=1);

namespace App\Adapters\ImageTransformer;

use Illuminate\Http\UploadedFile;

interface ImageTransformerContract
{
    const RESIZE_MODE_SCALE = 'scale';

    const RESIZE_MODE_SCALE_DOWN = 'scaleDown';

    const RESIZE_MODE_RESIZE = 'resize';

    const RESIZE_MODE_RESIZE_DOWN = 'resizeDown';

    public function image(UploadedFile $image): self;

    public function scale(?int $width = null, ?int $height = null): self;

    public function scaleDown(?int $width = null, ?int $height = null): self;

    public function resize(?int $width = null, ?int $height = null): self;

    public function resizeDown(?int $width = null, ?int $height = null): self;

    public function quality(int $value): self;

    public function get(): UploadedFile;
}
