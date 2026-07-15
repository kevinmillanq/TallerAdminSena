<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrainingCenter extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
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
}

