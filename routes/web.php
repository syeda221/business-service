<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\CompanyController;

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Admin authenticated routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });

    // Company routes
    Route::post('/companies/switch', [CompanyController::class, 'switch'])->name('companies.switch');
    Route::post('/companies/create', [CompanyController::class, 'store'])->name('companies.store');

    // Services Modules routes
    Route::get('/services', [ServicesController::class, 'index'])->name('services.index');
    Route::get('/services/{service_key}', [ServicesController::class, 'show'])->name('services.show');
    Route::post('/services/{service_key}/save-step', [ServicesController::class, 'saveStep'])->name('services.save_step');
    Route::post('/services/{service_key}/upload-document', [ServicesController::class, 'uploadDocument'])->name('services.upload_document');
    Route::post('/services/{service_key}/remove-document', [ServicesController::class, 'removeDocument'])->name('services.remove_document');
});
