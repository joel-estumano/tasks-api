<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\TasksController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
 * |--------------------------------------------------------------------------
 * | API Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register API routes for your application. These
 * | routes are loaded by the RouteServiceProvider within a group which
 * | is assigned the "api" middleware group. Enjoy building your API!
 * |
 */

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('user')->group(function () {
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/register', [UserController::class, 'register']);
    Route::middleware('auth:sanctum')->patch('/edit', [UserController::class, 'edit']);
    Route::middleware('auth:sanctum')->get('/logout', [UserController::class, 'logout']);
    Route::middleware('auth:sanctum')->get('/logout/all', [UserController::class, 'logoutAll']);
});

Route::prefix('tasks')->middleware('auth:sanctum')->group(function () {
    Route::post('/add', [TasksController::class, 'add']);
    Route::get('/list', [TasksController::class, 'list']);
    Route::get('/list/{date}', [TasksController::class, 'listByDate']);
    Route::get('/read/{id}', [TasksController::class, 'read']);
    Route::patch('/edit/{id}', [TasksController::class, 'edit']);
    Route::delete('delete/{id}', [TasksController::class, 'delete']);
});
