<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Route;

Route::prefix('/auth')->group(function() {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});