<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TranslationController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/translations', [TranslationController::class, 'create']);
    Route::put('/translations/{translationId}', [TranslationController::class, 'update']);
    Route::get('/translations/{identifier}', [TranslationController::class, 'show']);
    Route::get('/translations/search', [TranslationController::class, 'search']);
    Route::post('/translations/{translationId}/tags', [TranslationController::class, 'assignTags']);
    Route::get('/translations/tags/{tagName}', [TranslationController::class, 'getTranslationsByTag']);
    Route::get('/translations/export', [TranslationController::class, 'export']);
});
