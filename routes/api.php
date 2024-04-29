<?php

use App\Http\Controllers\ReportedTaskController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

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

Route::prefix('/reported-tasks')->group(
    function () {
        Route::get('/{id}', [ReportedTaskController::class, 'get']);
        Route::post('/search', [ReportedTaskController::class, 'search']);
        Route::post('/', [ReportedTaskController::class, 'create']);
        Route::patch('/{id}', [ReportedTaskController::class, 'patch']);
        Route::delete('/{id}', [ReportedTaskController::class, 'delete']);

    }
);
Route::prefix('/tasks')->group(
    function () {
        Route::get('/{id}', [TaskController::class, 'get']);
        Route::post('/search', [TaskController::class, 'search']);
        Route::post('/', [TaskController::class, 'create']);
        Route::delete('/{id}', [TaskController::class, 'delete']);
    }
);


