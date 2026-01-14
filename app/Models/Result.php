<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    /** @use HasFactory<\Database\Factories\ResultFactory> */
    use HasFactory;

    protected $fillable = [
        "images",
        "count_grafts",
        "count_months",
        "panch",
        "video_url",
    ];

    protected $casts = [
        'images' => 'array',
    ];

}
