<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Route;


// AUTH
Route::prefix('/auth')->group(function() {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});


// WITH AUTHENTICATION
Route::middleware('auth.api')->group(function() {
    // AUTH
    Route::prefix('/auth')->group(function() {
        Route::get('/logout', [AuthController::class, 'logout']);
    });
});