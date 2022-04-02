<?php

use App\Http\Controllers\Api\AdminTaskController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



// Public route
Route::post('/register' , [AuthController::class, 'register']);
Route::post('/login' , [AuthController::class, 'login']);

// Private route
Route::middleware(['auth:api'])->group(function () {
    Route::post('/logout' , [AuthController::class, 'logout']);

    // Member route
    Route::get('/task' , [TaskController::class , 'index']);
    Route::post('/task' , [TaskController::class , 'store']);
    Route::delete('/task/{task}' , [TaskController::class , 'destroy']);

    // Admin route
    Route::middleware('can:is_admin')->prefix('admin')->group(function () {
        Route::get('/users' , [AdminTaskController::class , 'getUsers']);
        Route::apiResource('/tasks' , AdminTaskController::class)->only('index' , 'update' , 'destroy');
        Route::post('/mention/{task}' , [AdminTaskController::class , 'mention']);
    });
    

});
