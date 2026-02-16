<?php

declare(strict_types=1);

namespace App\Models;

use App\Contracts\Commentable;
use App\Contracts\Likeable;
use App\Contracts\Taggable;
use App\Enums\RaffleType;
use App\Traits\HasCommented;
use App\Traits\HasLiked;
use App\Traits\HasTags;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Raffle extends Model implements Commentable, Likeable, Taggable
{
    /** @use HasFactory<\Database\Factories\RaffleFactory> */
    use HasCommented, HasFactory, HasLiked, HasTags;

    protected $fillable = [
        'title',
        'description',
        'meta_title',
        'meta_description',
        'content',
        'image',
        'video',
        'winner',
        'date_end',
        'type',
        'prize',
    ];

    protected $casts = [
        'date_end' => 'date',
        'content' => 'array',
        'type' => RaffleType::class,
        'prize' => 'array',
    ];

    public function getPrizeAmount(): float
    {
        return (float) ($this->prize['amount'] ?? 0);
    }

    public function winner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'winner_id');
    }
}
