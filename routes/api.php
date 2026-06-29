<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;

// ── PUBLIC ROUTES (no token needed) ──
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// ── PROTECTED ROUTES (token required) ──
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'me']);
    
    // Profile CRUD routes
    Route::get('/profile',        [ProfileController::class, 'show']);
    Route::put('/profile',        [ProfileController::class, 'update']);
    Route::delete('/profile',     [ProfileController::class, 'destroy']);
});