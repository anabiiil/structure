<?php

use App\Http\Controllers\User\Api\Auth\RegisterController;
use App\Http\Controllers\User\Api\Auth\LoginController;
use Illuminate\Support\Facades\Route;

// Authentication routes
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout']);
});
