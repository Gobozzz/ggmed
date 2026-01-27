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
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model implements Commentable, Likeable, Taggable
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasCommented, HasFactory, HasLiked, HasTags;

    protected $fillable = [
        'meta_title',
        'meta_description',
        'title',
        'description',
        'slug',
        'image',
        'content',
        'time_to_read',
        'filial_id',
        'author_id',
        'level_hipe',
    ];

    protected $casts = [
        'content' => 'array',
        'level_hipe' => LevelHipe::class,
    ];

    public function filial(): BelongsTo
    {
        return $this->belongsTo(Filial::class);
    }

    public function series(): BelongsToMany
    {
        return $this->belongsToMany(PostSeries::class, 'post_post_series', 'post_id', 'series_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(MoonshineUser::class, 'author_id');
    }
}
