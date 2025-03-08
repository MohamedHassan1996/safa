<?php

use App\Http\Controllers\Api\Private\User\UserController;
use App\Http\Controllers\Api\Public\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });

    Route::prefix('users')->group(function () {
        Route::get('', [UserController::class, 'index']);
        Route::post('create', [UserController::class, 'create']);
        Route::get('edit', [UserController::class, 'edit']);
        Route::put('update', [UserController::class, 'update']);
        Route::delete('destroy', [UserController::class, 'destroy']);
    });
});
