<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Worker extends Model
{
    /** @use HasFactory<\Database\Factories\WorkerFactory> */
    use HasFactory;

    protected $fillable = [
        'image',
        'surname',
        'name',
        'patronymic',
        'post',
        'filial_id',
    ];

    public function filial(): BelongsTo
    {
        return $this->belongsTo(Filial::class);
    }
}
