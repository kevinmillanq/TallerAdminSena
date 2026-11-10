<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('url'); // URL pública de Cloudinary
            $table->string('public_id')->nullable(); // Identificador único en Cloudinary
            $table->morphs('imageable'); // Crea imageable_type (string) e imageable_id (unsignedBigInteger) con índice
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};

