<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\LevelHipe;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StarGuest extends Model
{
    /** @use HasFactory<\Database\Factories\StarGuestFactory> */
    use HasFactory;

    protected $fillable = [
        'meta_title',
        'meta_description',
        'slug',
        'name',
        'points',
        'url',
        'content',
        'image',
        'level_hipe',
    ];

    protected $casts = [
        'content' => 'array',
        'points' => 'array',
        'level_hipe' => LevelHipe::class,
    ];
}
