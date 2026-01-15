<?php

declare(strict_types=1);

namespace App\Models;

use App\Contracts\Commentable;
use App\Traits\HasCommented;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Result extends Model implements Commentable
{
    /** @use HasFactory<\Database\Factories\ResultFactory> */
    use HasFactory, HasCommented;

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

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

}
