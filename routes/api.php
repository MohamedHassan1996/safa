<?php

use App\Http\Controllers\Api\Private\Charity\CharityCaseController;
use App\Http\Controllers\Api\Private\Charity\CharityCaseDocumentController;
use App\Http\Controllers\Api\Private\Charity\CharityController;
use App\Http\Controllers\Api\Private\Donation\DonationController;
use App\Http\Controllers\Api\Private\Select\SelectController;
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

    Route::prefix('selects')->group(function(){
        Route::get('', [SelectController::class, 'getSelects']);
    });

    Route::prefix('charities')->group(function () {
        Route::get('', [CharityController::class, 'index']);
        Route::post('create', [CharityController::class, 'create']);
        Route::get('edit', [CharityController::class, 'edit']);
        Route::put('update', [CharityController::class, 'update']);
        Route::delete('destroy', [CharityController::class, 'destroy']);
    });

    Route::prefix('charity-cases')->group(function () {
        Route::get('', [CharityCaseController::class, 'index']);
        Route::post('create', [CharityCaseController::class, 'create']);
        Route::get('edit', [CharityCaseController::class, 'edit']);
        Route::put('update', [CharityCaseController::class, 'update']);
        Route::delete('destroy', [CharityCaseController::class, 'destroy']);
    });

    Route::prefix('charity-case-documents')->group(function () {
        Route::get('', [CharityCaseDocumentController::class, 'index']);
        Route::post('create', [CharityCaseDocumentController::class, 'create']);
        Route::get('edit', [CharityCaseDocumentController::class, 'edit']);
        Route::put('update', [CharityCaseDocumentController::class, 'update']);
        Route::delete('destroy', [CharityCaseDocumentController::class, 'destroy']);
    });

    Route::prefix('donations')->group(function () {
        Route::get('', [DonationController::class, 'index']);
        Route::post('create', [DonationController::class, 'create']);
        Route::get('edit', [DonationController::class, 'edit']);
        Route::put('update', [DonationController::class, 'update']);
        Route::delete('destroy', [DonationController::class, 'destroy']);
    });


});


