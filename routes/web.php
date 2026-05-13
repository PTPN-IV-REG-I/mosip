<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TekpolController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

Route::prefix('mosip')->group(function () {

    // ── Auth Routes ────────────────────────────────────
    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout',[AuthController::class, 'logout'])->name('logout');

    // ── SSO Integration ────────────────────────────────
    Route::match(['get', 'post'], '/login/sso/consume', \App\Http\Controllers\Auth\SsoConsumeController::class)->name('login.sso.consume');
    Route::get('/login/sso/{provider}', function($provider) {
        return redirect(config('sso.portal_login_url'));
    })->name('login.sso');

    // ── Root redirect ──────────────────────────────────
    Route::get('/', fn() => redirect()->route('login'));

    // ── Protected Routes ────────────────────────────────
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', fn() => view('dashboard-new'))->name('dashboard');

        // Admin only
        Route::middleware(['role:Super Admin,Admin'])->group(function () {
            Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
            Route::get('/users',           [UserController::class, 'index'])->name('users.index');
            Route::post('/users',          [UserController::class, 'store'])->name('users.store');
            Route::put('/users/{user}',    [UserController::class, 'update'])->name('users.update');
            Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        });

        // Tekpol only
        Route::middleware(['role:Tekpol'])->group(function () {
            Route::get('/tekpol/dashboard', [TekpolController::class, 'dashboard'])->name('tekpol.dashboard');
            Route::post('/tekpol/refresh-cache', [TekpolController::class, 'refreshCache'])->name('tekpol.refresh');
        });
    });

});
