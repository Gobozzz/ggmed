<?php

declare(strict_types=1);

namespace App\MoonShine\Fields;

use Illuminate\Contracts\Support\Renderable;
use MoonShine\UI\Fields\File;

class Video extends File
{
    protected string $view = 'admin.fields.video';

    protected function resolvePreview(): Renderable|string
    {
        return view($this->view, ['files' => $this->getFiles()->toArray()]);
    }
}
