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

class VideoReview extends Model implements Commentable, Likeable, Taggable
{
    /** @use HasFactory<\Database\Factories\VideoReviewFactory> */
    use HasCommented, HasFactory, HasLiked, HasTags;

    protected $fillable = [
        'preview',
        'video',
        'title',
        'content',
        'filial_id',
        'images_before',
        'level_hipe',
    ];

    protected $casts = [
        'images_before' => 'array',
        'level_hipe' => LevelHipe::class,
    ];

    public function filial(): BelongsTo
    {
        return $this->belongsTo(Filial::class);
    }
}
