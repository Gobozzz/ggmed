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

class Result extends Model implements Commentable, Likeable, Taggable
{
    /** @use HasFactory<\Database\Factories\ResultFactory> */
    use HasCommented, HasFactory, HasLiked, HasTags;

    protected $fillable = [
        'images',
        'count_grafts',
        'count_months',
        'panch',
        'level_hipe',
    ];

    protected $casts = [
        'images' => 'array',
        'level_hipe' => LevelHipe::class,
    ];
}
