<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\ImageController;

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

Route::get('/', function () {
    return view('welcome');
});

// Rutas para adjuntar y visualizar imágenes en Cloudinary
Route::get('/images', [ImageController::class, 'index'])->name('images.index');
Route::get('/images/create', [ImageController::class, 'create'])->name('images.create');
Route::post('/images', [ImageController::class, 'store'])->name('images.store');
Route::delete('/images/{image}', [ImageController::class, 'destroy'])->name('images.destroy');

// Rutas formulario Áreas
Route::get('/areas/create', [AreaController::class, 'create'])->name('areas.create');
Route::post('/areas', [AreaController::class, 'store'])->name('areas.store');
Route::put('/areas/{area}', [AreaController::class, 'update'])->name('areas.update');
