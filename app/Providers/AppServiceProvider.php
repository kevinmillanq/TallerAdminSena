<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Models\Area;
use App\Models\TrainingCenter;
use App\Models\Computer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Compatibilidad de tipo 'set' en SQLite para pruebas unitarias
        \Illuminate\Database\Schema\Grammars\SQLiteGrammar::macro('typeSet', function () {
            return 'varchar';
        });

        Relation::morphMap([
            'area' => Area::class,
            'Area' => Area::class,
            'areas' => Area::class,
            'training_center' => TrainingCenter::class,
            'training_centers' => TrainingCenter::class,
            'TrainingCenter' => TrainingCenter::class,
            'computer' => Computer::class,
            'computers' => Computer::class,
            'Computer' => Computer::class,
        ]);
    }
}
