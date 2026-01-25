<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vacancy extends Model
{
    /** @use HasFactory<\Database\Factories\VacancyFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'address',
        'url',
        'salary',
        'valute',
        'what_pay',
        'responsible',
        'author_id',
        'filial_id',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(MoonshineUser::class, 'author_id');
    }

    public function filial(): BelongsTo
    {
        return $this->belongsTo(Filial::class, 'filial_id');
    }
}
