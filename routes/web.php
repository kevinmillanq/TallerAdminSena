<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\controllers\Areacontroller;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ruta formulario
Route::get('/areas/create', [AreaController::class, 'create'])->name('areas.create');

//POST enviar y guardar
Route::post('/areas', [AreaController::class, 'store'])->name('areas.store');

// UPDATE actualizar area especifica
Route::put('/areas/{area}', [AreaController::class,'update'])->name('areas.update');