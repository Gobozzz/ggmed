<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    /** @use HasFactory<\Database\Factories\PageFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'content',
        'meta_title',
        'meta_description',
        'meta_robots',
        'og_image',
    ];

    protected $casts = ['content' => 'array'];
}
