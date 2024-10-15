<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Sanctum protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::resource('tasks',TaskController::class)->except(['update','delete','edit']);
    // Route::post('/tasks', [TaskController::class, 'store']);
    // Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/logout', [AuthController::class, 'logout']);

});
