<?php

declare(strict_types=1);

namespace App\Models;

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
    ];

    protected $casts = [
        'content' => 'array',
        'points' => 'array',
    ];
}
