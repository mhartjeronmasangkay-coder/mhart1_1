<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/export-users', [ExportController::class, 'export']);

// ── PUBLIC ROUTES (no token needed) ──
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/import-users', [ImportController::class, 'import']);
// ── PUBLIC IMPORT ROUTES (used by the UI) ──
Route::post('/subjects/import', [ImportController::class, 'import']);
Route::get('/subjects/import/{jobId}/status', [ImportController::class, 'status']);
Route::get('/subjects/export', [ExportController::class, 'export']);


// ── PROTECTED ROUTES (token required) ──
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Profile CRUD routes
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::delete('/profile', [ProfileController::class, 'destroy']);

    // PDF generation
    Route::post('/questions/generate-pdf', [PdfController::class, 'generate']);
});
