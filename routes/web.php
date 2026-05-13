<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Central\CompanyRegistrationController;
use App\Http\Controllers\Central\SuperAdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/register-company', [CompanyRegistrationController::class, 'create'])->name('companies.create');
    Route::post('/register-company', [CompanyRegistrationController::class, 'store'])->name('companies.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [SuperAdminController::class, 'index'])
        ->middleware('can:access-super-admin')
        ->name('admin.dashboard');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

require __DIR__.'/tenant.php';
