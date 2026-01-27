<?php

declare(strict_types=1);

namespace App\Models;

use App\Contracts\Commentable;
use App\Contracts\Likeable;
use App\Contracts\Taggable;
use App\Enums\LevelHipe;
use App\Traits\HasCommented;
use App\Traits\HasLiked;
use App\Traits\HasTags;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model implements Commentable, Likeable, Taggable
{
    /** @use HasFactory<\Database\Factories\TestFactory> */
    use HasCommented, HasFactory, HasLiked, HasTags;

    protected $fillable = [
        'title',
        'description',
        'meta_title',
        'meta_description',
        'exercises',
        'image',
        'level_hipe',
        'level_hipe' => LevelHipe::class,
    ];

    protected $casts = [
        'exercises' => 'array',
        'level_hipe' => LevelHipe::class,
    ];
}
