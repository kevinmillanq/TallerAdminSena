<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\TrainingCenterController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ComputerController;
use App\Http\Controllers\ApprenticeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/teachers', [TeacherController::class, 'index'])->name('api.v1.teachers.index');
Route::get('/areas', [AreaController::class, 'index'])->name('api.v1.areas.index');
Route::get('/training-centers', [TrainingCenterController::class, 'index'])->name('api.v1.training-centers.index');
Route::get('/courses', [CourseController::class, 'index'])->name('api.v1.courses.index');
Route::get('/computers', [ComputerController::class, 'index'])->name('api.v1.computers.index');
Route::get('/apprentices', [ApprenticeController::class, 'index'])->name('api.v1.apprentices.index');