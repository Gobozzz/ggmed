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

class Product extends Model implements Commentable, Likeable, Taggable
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasCommented, HasFactory, HasLiked, HasTags;

    protected $fillable = [
        'meta_title',
        'meta_description',
        'title',
        'slug',
        'description',
        'images',
        'price',
        'article',
        'old_price',
        'structure',
        'brand',
        'is_have',
        'content',
        'level_hipe',
        'price_coin',
        'can_buy_only_coin',
    ];

    protected $casts = [
        'content' => 'array',
        'images' => 'array',
        'is_have' => 'boolean',
        'can_buy_only_coin' => 'boolean',
        'level_hipe' => LevelHipe::class,
    ];
}
