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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('course_number');
            $table->set('day', ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes']);
            //Atributos foraneos
            $table->unsignedBigInteger('area_id')->nullable();
            $table->unsignedBigInteger('training_center_id')->nullable();
           //referenciando la tabla users
            $table->foreign('area_id')
                ->references('id')
                ->on('areas')->onDelete('set null');
            //referenciando la tabla categorias
            $table->foreign('training_center_id')
            ->references('id')
            ->on('training_centers')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
