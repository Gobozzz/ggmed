<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    /** @use HasFactory<\Database\Factories\RecommendationFactory> */
    use HasFactory;

    protected $fillable = [
        'image',
        'title',
        'slug',
        'description',
        'meta_title',
        'meta_description',
        'content',
    ];

    protected $casts = [
        'content' => 'array',
    ];
}
