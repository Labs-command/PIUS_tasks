<?php

use App\Http\Controllers\ReportedTaskController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
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
        Route::get('/', [ReportedTaskController::class, 'search']);
        Route::post('/', [ReportedTaskController::class, 'create']);
        //Route::put('/{id}', [ReportedTaskController::class, 'replace']);
        Route::patch('/{id}', [ReportedTaskController::class, 'patch']);
        Route::delete('/{id}', [ReportedTaskController::class, 'delete']);

    }
);
Route::prefix('/tasks')->group(
    function () {
        Route::get('/{id}', [TaskController::class, 'get']);
        Route::get('/', [TaskController::class, 'search']);
        Route::post('/', [TaskController::class, 'create']);
        Route::delete('/{id}', [TaskController::class, 'delete']);
    }
);


