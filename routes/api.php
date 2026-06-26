<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\AdminController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

Route::get('/subjects',            [QuizController::class, 'getSubjects']);
Route::get('/questions/{levelId}', [QuizController::class, 'getQuestions']);

// 👇 Admin routes
Route::get('/admin/stats',    [AdminController::class, 'getStats']);
Route::get('/admin/students', [AdminController::class, 'getStudents']);

Route::get('/admin/subjects',         [AdminController::class, 'getSubjects']);
Route::post('/admin/subjects',        [AdminController::class, 'createSubject']);
Route::put('/admin/subjects/{id}',    [AdminController::class, 'updateSubject']);
Route::delete('/admin/subjects/{id}', [AdminController::class, 'deleteSubject']);
