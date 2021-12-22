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


// ONLY SCHOOL
Route::middleware('school.api')->group(function() {
    // CLASSROOM
    Route::prefix('classrooms')->group(function() {
        Route::get('/', [ClassroomController::class, 'index']);
        Route::post('/', [ClassroomController::class, 'store']);

        Route::prefix('/{classroom:id}')->group(function() {
            Route::get('/students', [ClassroomController::class, 'indexStudent']);
            Route::post('/students', [ClassroomController::class, 'storeStudent']);
        });
    });
});


// ONLY PAYER
Route::middleware('payer.api')->group(function() {
    // CHILDREN
    Route::prefix('/children')->group(function() {
        Route::get('/', [ChildController::class, 'index']);
    });
});