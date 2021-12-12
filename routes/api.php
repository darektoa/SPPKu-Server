<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Route;

Route::prefix('/auth')->group(function() {
    Route::post('/register', [AuthController::class, 'register']);
});