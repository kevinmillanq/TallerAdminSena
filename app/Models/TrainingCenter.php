<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class TrainingCenter extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
    ];

    // Un centro tiene muchos profesores
    public function teachers(): HasMany
    {
        return $this->hasMany(Teacher::class);
    }

    // Un centro tiene muchos cursos
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
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

