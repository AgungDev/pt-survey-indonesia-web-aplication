<?php

use App\Presentation\Http\Controllers\Auth\LoginController;
use App\Presentation\Http\Controllers\DashboardController;
use App\Presentation\Http\Controllers\ImportController;
use App\Presentation\Http\Controllers\InspectionController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.perform');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Import routes - Super Admin & Admin only
    Route::middleware('role:Super Admin,Admin')->group(function () {
        Route::get('/imports', [ImportController::class, 'index'])->name('imports.index');
        Route::post('/imports', [ImportController::class, 'store'])->name('imports.store');
    });

    // Inspection routes - all authenticated users
    Route::get('/inspections', [InspectionController::class, 'index'])->name('inspections.index');
    
    // Create & store - Super Admin, Admin, Inspector
    Route::middleware('role:Super Admin,Admin,Inspector')->group(function () {
        Route::get('/inspections/create', [InspectionController::class, 'create'])->name('inspections.create');
        Route::post('/inspections', [InspectionController::class, 'store'])->name('inspections.store');
    });
    
    // Approve & reject - Super Admin & Supervisor
    Route::middleware('role:Super Admin,Supervisor')->group(function () {
        Route::post('/inspections/{id}/approve', [InspectionController::class, 'approve'])->name('inspections.approve');
        Route::post('/inspections/{id}/reject', [InspectionController::class, 'reject'])->name('inspections.reject');
    });
});
