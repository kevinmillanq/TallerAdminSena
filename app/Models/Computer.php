<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Computer extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'brand',
    ];

    public function apprentices(): HasMany
    {
        return $this->hasMany(Apprentice::class);
    }

    // Relación polimórfica para imagen
    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
