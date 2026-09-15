<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MonitorController;
use App\Http\Controllers\SsoController;

// 1. THIS MUST BE OUTSIDE THE MIDDLEWARE GROUP
Route::get('/monitor/sso/callback', [SsoController::class, 'handleCallback'])->name('sso.callback');


// 2. ONLY THESE DASHBOARD ROUTES SHOULD BE INSIDE THE MIDDLEWARE
Route::middleware(['auth'])->group(function () {
    Route::get('/monitor', [MonitorController::class, 'index'])->name('monitor.index');
    Route::post('/monitor/store', [MonitorController::class, 'store'])->name('monitor.store');
    Route::post('/monitor/update/{id}', [MonitorController::class, 'update'])->name('monitor.update');
    Route::delete('/monitor/delete/{id}', [MonitorController::class, 'destroy'])->name('monitor.destroy');
});