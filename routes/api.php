<?php

use App\Http\Controllers\api\v1\admin\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\v1\AuthenticationController;
use App\Http\Controllers\api\v1\user\HealthDataController;
use App\Http\Controllers\api\v1\user\UserController;
use App\Http\Controllers\api\v1\user\WeightLogController;
use App\Http\Middleware\AdminMiddleware;

Route::prefix('v1')->group(function () {
    // Public routes
    Route::post('/register', [AuthenticationController::class, 'registerUser']);
    Route::post('/login', [AuthenticationController::class, 'loginUser']);

    // Protected routes for authenticated users
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthenticationController::class, 'logoutUser'])->middleware('throttle:30,1');

        // User-specific routes
        Route::get('profile', [UserController::class, 'view'])->middleware('throttle:20,1');
        Route::put('profile', [UserController::class, 'update'])->middleware('throttle:10,1');
        Route::delete('profile', [UserController::class, 'destroy'])->middleware('throttle:5,1');

        // Weight log routes
        Route::get('weight-logs', [WeightLogController::class, 'index'])->middleware('throttle:30,1');
        Route::post('weight-logs', [WeightLogController::class, 'store'])->middleware('throttle:10,1');
        Route::get('weight-logs/{weightLog}', [WeightLogController::class, 'show'])->middleware('throttle:20,1');
        Route::put('weight-logs/{weightLog}', [WeightLogController::class, 'update'])->middleware('throttle:10,1');
        Route::delete('weight-logs/{weightLog}', [WeightLogController::class, 'destroy'])->middleware('throttle:5,1');

        // Health data routes
        Route::post('health-data', [HealthDataController::class, 'store'])->middleware('throttle:10,1');
        Route::get('health-data/{healthData}', [HealthDataController::class, 'show'])->middleware('throttle:20,1');
        Route::put('health-data/{healthData}', [HealthDataController::class, 'update'])->middleware('throttle:10,1');
        Route::delete('health-data/{healthData}', [HealthDataController::class, 'destroy'])->middleware('throttle:5,1');
        Route::get('user-health-data/{userId}', [HealthDataController::class, 'getHealthDataByUser']);

        // Admin-specific routes
        Route::prefix('admin')->middleware(AdminMiddleware::class)->group(function () {
            Route::get('users', [AdminController::class, 'index']);
            Route::get('users/{user}', [AdminController::class, 'show'])->missing(function () {
                return response()->json(['message' => 'User not found.'], 404);
            });
            Route::delete('users/{user}', [AdminController::class, 'destroy']);
            Route::put('users/{user}/promote', [AdminController::class, 'promote']);

            Route::get('health-data', [AdminController::class, 'viewHealthData']);
            Route::get('health-data/{healthData}', [AdminController::class, 'showHealthData']);
            Route::delete('health-data/{healthData}', [AdminController::class, 'destroyHealthData']);

            Route::get('weight-logs', [AdminController::class, 'viewWeightLogs']);
            Route::get('weight-logs/{weightLog}', [AdminController::class, 'showWeightLog']);
            Route::delete('weight-logs/{weightLog}', [AdminController::class, 'destroyWeightLog']);
        });

    });
});
