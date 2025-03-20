<?php

use App\Http\Controllers\Api\Private\Charity\CharityCaseController;
use App\Http\Controllers\Api\Private\Charity\CharityCaseDocumentController;
use App\Http\Controllers\Api\Private\Charity\CharityController;
use App\Http\Controllers\Api\Private\Donation\DonationController;
use App\Http\Controllers\Api\Private\Select\SelectController;
use App\Http\Controllers\Api\Private\User\UserController;
use App\Http\Controllers\Api\Public\Auth\AuthController;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity;

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


Route::get("v1/logs/charity-cases", function () {

    $logs = Activity::where('log_name', 'charityCase')->latest()->get();

    $arrayOfLogs = [];
    $actionTranslations = [
        'created' => 'created',
        'updated' => 'updated',
        'deleted' => 'deleted',
    ];

    foreach ($logs as $log) {
        $subjectType = $log->subject_type; // "App\\Models\\CharityCase\\CharityCase"
        $subjectId = $log->subject_id;

        $value = null;
        if (class_exists($subjectType)) {
            $modelInstance = $subjectType::find($subjectId);
            if ($modelInstance) {
                $logMainColumn = $modelInstance->logMainColumn ?? null;
                $value = $logMainColumn ? $modelInstance->{$logMainColumn} : null;
            }
        }

        // Extract the updated properties
        $props = [];
        if ($log->event === 'updated' && isset($log->properties['attributes'], $log->properties['old'])) {
            foreach ($log->properties['attributes'] as $key => $newValue) {
                $oldValue = $log->properties['old'][$key] ?? null;

                // Exclude created_at and updated_at
                if (in_array($key, ['created_at', 'updated_at'])) {
                    continue;
                }

                $camelKey = Str::camel($key); // Convert snake_case to camelCase

                if ($oldValue !== $newValue) {
                    $props[$camelKey] = [
                        'old' => $oldValue,
                        'new' => $newValue
                    ];
                }
            }
        }

        $arrayOfLogs[] = [
            'userId' => $log->causer_id,
            'userName' => optional(User::find($log->causer_id))->name,
            'userAvatar' => optional($log->causer)->avatar,
            'date' => Carbon::parse($log->created_at)->format('d/m/Y'),
            'time' => Carbon::parse($log->created_at)->format('H:i'),
            'model' => [
                'id' => $log->subject_id,
                'name' => $log->log_name,
                'title' => $value,
            ],
            'actionType' => $actionTranslations[$log->event] ?? $log->event,
            'properties' => $props, // Only changed properties
        ];
    }

    return $arrayOfLogs;
});


Route::get("v1/logs/donations", function () {

    $logs = Activity::where('log_name', 'donation')->latest()->get();

    $arrayOfLogs = [];
    $actionTranslations = [
        'created' => 'created',
        'updated' => 'updated',
        'deleted' => 'deleted',
    ];

    foreach ($logs as $log) {
        $subjectType = $log->subject_type; // "App\\Models\\CharityCase\\CharityCase"
        $subjectId = $log->subject_id;

        $value = null;
        if (class_exists($subjectType)) {
            $modelInstance = $subjectType::find($subjectId);
            if ($modelInstance) {
                $logMainColumn = $modelInstance->logMainColumn ?? null;
                $value = $logMainColumn ? $modelInstance->{$logMainColumn} : null;
            }
        }

        // Extract the updated properties
        $props = [];
        if ($log->event === 'updated' && isset($log->properties['attributes'], $log->properties['old'])) {
            foreach ($log->properties['attributes'] as $key => $newValue) {
                $oldValue = $log->properties['old'][$key] ?? null;

                // Exclude created_at and updated_at
                if (in_array($key, ['created_at', 'updated_at'])) {
                    continue;
                }

                $camelKey = Str::camel($key); // Convert snake_case to camelCase

                if ($oldValue !== $newValue) {
                    $props[$camelKey] = [
                        'old' => $oldValue,
                        'new' => $newValue
                    ];
                }
            }
        }

        $arrayOfLogs[] = [
            'userId' => $log->causer_id,
            'userName' => optional(User::find($log->causer_id))->name,
            'userAvatar' => optional($log->causer)->avatar,
            'date' => Carbon::parse($log->created_at)->format('d/m/Y'),
            'time' => Carbon::parse($log->created_at)->format('H:i'),
            'model' => [
                'id' => $log->subject_id,
                'name' => $log->log_name,
                'title' => $value,
            ],
            'actionType' => $actionTranslations[$log->event] ?? $log->event,
            'properties' => $props, // Only changed properties
        ];
    }

    return $arrayOfLogs;
});

