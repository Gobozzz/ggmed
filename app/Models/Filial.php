<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Filial extends Model
{
    /** @use HasFactory<\Database\Factories\FilialFactory> */
    use HasFactory;

    protected $fillable = [
        "meta_title",
        "meta_description",
        "slug",
        "name",
        "video",
        "image",
        "year",
        "address",
        "work_time",
        "map_code",
        "manager_id",
    ];

    public function manager(): BelongsTo
    {
        return $this->belongsTo(MoonshineUser::class, 'manager_id');
    }

}
