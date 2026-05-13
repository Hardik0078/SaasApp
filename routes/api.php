<?php

use App\Http\Controllers\Api\AuthTokenController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\TaskCommentController;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Support\Facades\Route;

Route::prefix('tenant/{tenant:slug}')
    ->middleware(['tenant', 'throttle:api'])
    ->group(function () {
        Route::post('/tokens', [AuthTokenController::class, 'store'])->middleware('guest')->name('api.tokens.store');

        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/projects', [ProjectController::class, 'index'])->name('api.projects.index');
            Route::post('/projects', [ProjectController::class, 'store'])->name('api.projects.store');
            Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('api.projects.show');
            Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('api.projects.update');

            Route::get('/tasks', [TaskController::class, 'index'])->name('api.tasks.index');
            Route::post('/tasks', [TaskController::class, 'store'])->name('api.tasks.store');
            Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('api.tasks.show');
            Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('api.tasks.update');

            Route::post('/tasks/{task}/comments', [TaskCommentController::class, 'store'])->name('api.tasks.comments.store');

            Route::get('/subscription', [SubscriptionController::class, 'show'])->name('api.subscription.show');
        });
    });
