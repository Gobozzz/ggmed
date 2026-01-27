<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\LevelHipe;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    /** @use HasFactory<\Database\Factories\AnnouncementFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'video',
        'meta_title',
        'meta_description',
        'content',
        'level_hipe',
    ];

    protected $casts = ['content' => 'array', 'level_hipe' => LevelHipe::class];
}
