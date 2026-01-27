<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\LevelHipe;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mention extends Model
{
    /** @use HasFactory<\Database\Factories\MentionFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'url',
        'level_hipe',
    ];

    protected $casts = [
        'level_hipe' => LevelHipe::class,
    ];
}
