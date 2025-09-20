<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Authentication routes (public)
Route::post('login', [AuthController::class, 'login']);


// Protected routes
Route::middleware('access.api')->group(function () {
    // Auth management
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'user']);
    Route::get('tokens', [AuthController::class, 'tokens']);
    Route::delete('tokens/{tokenId}', [AuthController::class, 'revokeToken']);
    Route::delete('tokens', [AuthController::class, 'revokeAllTokens']);
});

// ACCESS School Management System API Routes
// PROTECTED ROUTES (commented out for testing)

Route::prefix('access')->middleware(['access.api'])->group(function () {
    Route::prefix('students/{studentId}')->group(function () {
        Route::get('info', [StudentController::class, 'getInfo']);
        Route::post('authenticate', [StudentController::class, 'authenticate']);
        Route::get('curriculum', [StudentController::class, 'getCurriculum']);
        Route::get('grades', [StudentController::class, 'getGrades']);
        Route::get('schedule', [StudentController::class, 'getSchedule']);
        Route::get('term-grades', [StudentController::class, 'getTermGrades']);
        Route::get('assessment', [StudentController::class, 'getAssessment']);
        Route::get('balance', [StudentController::class, 'getBalance']);
        Route::get('ledger-history', [StudentController::class, 'getLedgerHistory']);
        Route::post('assess', [StudentController::class, 'assess']);
    });
});

