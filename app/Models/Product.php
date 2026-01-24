<?php

namespace App\Models;

use App\Contracts\Commentable;
use App\Contracts\Likeable;
use App\Contracts\Taggable;
use App\Traits\HasCommented;
use App\Traits\HasLiked;
use App\Traits\HasTags;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model implements Commentable, Likeable, Taggable
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory, HasCommented, HasLiked, HasTags;

    protected $fillable = [
        "meta_title",
        "meta_description",
        "title",
        "description",
        "images",
        "price",
        "old_price",
        "structure",
        "brand",
        "is_have",
        "content",
    ];

    protected $casts = [
        'content' => 'array',
        'images' => 'array',
        'is_have' => 'boolean',
    ];

}
