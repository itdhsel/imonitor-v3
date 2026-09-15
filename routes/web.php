<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MonitorController;
use App\Http\Controllers\SsoController;

// SSO Callback Route
Route::get('/sso/callback', [SsoController::class, 'handleCallback']);

// Bypass middleware temporarily by commenting out the group wrapper
// Route::middleware(['auth', 'single.session'])->group(function () {
    
    Route::get('/monitor', [MonitorController::class, 'index'])->name('monitor.index');
    Route::post('/monitor/store', [MonitorController::class, 'store'])->name('monitor.store');
    Route::post('/monitor/update/{id}', [MonitorController::class, 'update'])->name('monitor.update');
    Route::delete('/monitor/delete/{id}', [MonitorController::class, 'destroy'])->name('monitor.destroy');

// }); // Make sure to comment out this closing bracket too!