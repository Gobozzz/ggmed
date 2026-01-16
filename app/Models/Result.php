<?php

declare(strict_types=1);

namespace App\Models;

use App\Contracts\Commentable;
use App\Contracts\Likeable;
use App\Contracts\Taggable;
use App\Traits\HasCommented;
use App\Traits\HasLiked;
use App\Traits\HasTags;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Result extends Model implements Commentable, Likeable, Taggable
{
    /** @use HasFactory<\Database\Factories\ResultFactory> */
    use HasFactory, HasCommented, HasLiked, HasTags;

    protected $fillable = [
        "images",
        "count_grafts",
        "count_months",
        "panch",
        "video_url",
    ];

    protected $casts = [
        'images' => 'array',
    ];

}
