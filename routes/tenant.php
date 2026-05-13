<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Tenant\BillingInvoiceController;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\ProjectController;
use App\Http\Controllers\Tenant\SubscriptionController;
use App\Http\Controllers\Tenant\TaskController;
use App\Http\Controllers\Tenant\UserController;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Route;

Route::prefix('{tenant:slug}')
    ->middleware(['impersonate.superadmin', 'tenant', SubstituteBindings::class])
    ->group(function () {
        Route::middleware('guest')->group(function () {
            Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('tenant.login');
            Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('tenant.login.store');
        });

        Route::middleware('auth')->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('tenant.dashboard');

            Route::resource('projects', ProjectController::class)->names('tenant.projects');
            Route::resource('tasks', TaskController::class)->except(['destroy'])->names('tenant.tasks');

            Route::get('/users', [UserController::class, 'index'])->name('tenant.users.index');
            Route::post('/users', [UserController::class, 'store'])->name('tenant.users.store');

            Route::get('/subscription', [SubscriptionController::class, 'show'])->name('tenant.subscription.show');
            Route::post('/subscription', [SubscriptionController::class, 'update'])->name('tenant.subscription.update');
            Route::post('/subscription/checkout', [SubscriptionController::class, 'checkout'])->name('tenant.subscription.checkout');
            Route::get('/subscription/success', [SubscriptionController::class, 'success'])->name('tenant.subscription.success');
            Route::get('/subscription/invoices/{invoice}/pdf', [BillingInvoiceController::class, 'download'])->name('tenant.subscription.invoices.pdf');

            Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('tenant.logout');
        });
    });
