<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\User\Api\Clinic\ClinicRegistrationController;
use App\Http\Controllers\User\Api\SpecialityController;

Route::get('/user', static function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Clinic Registration Route
Route::post('/clinic/register', [ClinicRegistrationController::class, 'register']);

// Specialities Routes
Route::get('/specialities', [SpecialityController::class, 'index']);
Route::get('/specialities/hierarchical', [SpecialityController::class, 'hierarchical']);
