<?php

use App\Http\Controllers\User\Api\Auth\RegisterController;
use App\Http\Controllers\User\Api\Auth\LoginController;
use Illuminate\Support\Facades\Route;

// Authentication routes
Route::post('/register', [RegisterController::class, 'register']);
// i want to login with phone number, user add his phone number , if the number in db send otp code , else return error number is not exists
Route::post('/login', [LoginController::class, 'checkLogin']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
});
