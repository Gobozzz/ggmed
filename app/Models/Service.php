<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\LevelHipe;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceFactory> */
    use HasFactory;

    protected $fillable = [
        'meta_title',
        'meta_description',
        'name',
        'slug',
        'price',
        'image',
        'content',
        'description',
        'is_start_price',
        'parent_id',
        'level_hipe',
    ];

    protected $casts = [
        'content' => 'array',
        'is_start_price' => 'boolean',
        'level_hipe' => LevelHipe::class,
    ];

    public function children(): HasMany
    {
        return $this->hasMany(Service::class, 'parent_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'parent_id');
    }

    public function filials(): BelongsToMany
    {
        return $this->belongsToMany(Filial::class, 'filial_service')
            ->withPivot('meta_title', 'meta_description', 'price', 'is_start_price');
    }
}
