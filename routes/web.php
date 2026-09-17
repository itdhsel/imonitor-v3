<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MonitorController;
use App\Http\Controllers\SsoController;

// 1. Updated SSO Callback Route (PUBLIC)
Route::get('/imonitor/sso/callback', [SsoController::class, 'handleCallback']);

// Protected Application Routes (STRICTLY FOR LOGGED IN USERS)
Route::middleware(['auth', 'single.session'])->group(function () {
    
    // Dashboard Route
    Route::get('/imonitor', [MonitorController::class, 'index'])->name('monitor.index');

    // Restrict Store & Update to Admin and Pharmacy staff
    Route::middleware(['role:admin,pharmacy'])->group(function () {
        Route::post('/imonitor/store', [MonitorController::class, 'store'])->name('monitor.store');
        Route::post('/imonitor/update/{id}', [MonitorController::class, 'update'])->name('monitor.update');
    });

    // Restrict Delete strictly to Admin level
    Route::middleware(['role:admin'])->group(function () {
        Route::delete('/imonitor/delete/{id}', [MonitorController::class, 'destroy'])->name('monitor.destroy');
    });
});

// 2. Catch unauthenticated users (PUBLIC - OUTSIDE THE MIDDLEWARE)
Route::get('/', function () {
    // Redirects the user back to your SSO login page
    return redirect('http://hsel-sso.ddev.site/login'); 
})->name('login');

Route::get('/counselling', [App\Http\Controllers\ConsultingController::class, 'index'])->name('counselling.index');
Route::post('/counselling', [App\Http\Controllers\ConsultingController::class, 'store'])->name('counselling.store');
Route::get('/collection', [App\Http\Controllers\CollectionController::class, 'index'])->name('collection.index');
Route::post('/collection/update', [App\Http\Controllers\CollectionController::class, 'store'])->name('collection.store');
