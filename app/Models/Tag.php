<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Tag extends Model
{
    /** @use HasFactory<\Database\Factories\TagFactory> */
    use HasFactory;

    protected $fillable = [
        "name",
        "slug",
    ];

    public function questions(): MorphToMany
    {
        return $this->morphedByMany(Question::class, 'entity', 'tag_entity');
    }

    public function results(): MorphToMany
    {
        return $this->morphedByMany(Result::class, 'entity', 'tag_entity');
    }

}
