<?php

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
    use HasFactory, HasCommented, HasLiked;

    protected $fillable = [
        'title',
        'answer',
        'user_id',
        'is_hot',
    ];

    protected $casts = [
        'is_hot' => 'boolean',
        'answer' => 'array',
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
