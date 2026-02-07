<?php

declare(strict_types=1);

namespace App\Models;

use App\Contracts\Commentable;
use App\Contracts\Likeable;
use App\Traits\HasCommented;
use App\Traits\HasLiked;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Question extends Model implements Commentable, Likeable
{
    /** @use HasFactory<\Database\Factories\QuestionFactory> */
    use HasCommented, HasFactory, HasLiked;

    protected $fillable = [
        'title',
        'answer',
        'user_id',
        'is_hot',
        'images',
        'is_published',
    ];

    protected $casts = [
        'is_hot' => 'boolean',
        'is_published' => 'boolean',
        'answer' => 'array',
        'images' => 'array',
    ];

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'entity', 'tag_entity');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
