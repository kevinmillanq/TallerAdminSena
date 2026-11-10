<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'public_id',
        'imageable_id',
        'imageable_type',
    ];

    /**
     * Obtiene el modelo padre al que pertenece la imagen (Area, TrainingCenter o Computer).
     * Relación polimórfica uno a uno o uno a muchos.
     */
    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }
}

