<?php

use App\Presentation\Http\Controllers\Auth\ForgotPasswordController;
use App\Presentation\Http\Controllers\Auth\LoginController;
use App\Presentation\Http\Controllers\Auth\ResetPasswordController;
use App\Presentation\Http\Controllers\DashboardController;
use App\Presentation\Http\Controllers\ImportController;
use App\Presentation\Http\Controllers\InspectionController;
use App\Http\Controllers\DebugController;
use Illuminate\Support\Facades\Route;

// Debug routes (only in debug mode)
if (config('app.debug')) {
    Route::get('/debug/fix-roles', [DebugController::class, 'fixRoles']);
}

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.perform');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/logout', function () {
    // Redirect GET requests to login with a message
    return redirect('/login')->with('error', 'Please use the logout button to sign out.');
});

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::redirect('/dashboard', '/');

    Route::middleware('role:Super Admin,Admin')->group(function () {
        Route::get('/imports', [ImportController::class, 'index'])->name('imports.index');
        Route::post('/imports', [ImportController::class, 'store'])->name('imports.store');
        Route::view('/equipments', 'pages.equipments.index')->name('equipments.index');
    });

    Route::middleware('role:Super Admin')->group(function () {
        Route::view('/users', 'pages.users.index')->name('users.index');
        Route::view('/roles', 'pages.roles.index')->name('roles.index');
        Route::view('/settings', 'pages.settings.index')->name('settings.index');
    });

    Route::middleware('role:Super Admin,Admin,Supervisor')->group(function () {
        Route::view('/reports', 'pages.reports.index')->name('reports.index');
    });

    Route::get('/inspections', [InspectionController::class, 'index'])->name('inspections.index');

    Route::middleware('role:Super Admin,Admin,Inspector')->group(function () {
        Route::get('/inspections/create', [InspectionController::class, 'create'])->name('inspections.create');
        Route::post('/inspections', [InspectionController::class, 'store'])->name('inspections.store');
    });

    Route::middleware('role:Super Admin,Supervisor')->group(function () {
        Route::post('/inspections/{id}/approve', [InspectionController::class, 'approve'])->name('inspections.approve');
        Route::post('/inspections/{id}/reject', [InspectionController::class, 'reject'])->name('inspections.reject');
    });
});
