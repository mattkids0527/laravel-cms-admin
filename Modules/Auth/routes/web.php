<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\App\Http\Controllers\AuthController;
use Modules\Auth\App\Livewire\Login;

Route::middleware('web')->prefix('admin')->name('admin.')->group(function () {

    Route::middleware('guest:admin')->group(function () {
        Route::get('login', Login::class)->name('login');
    });

    Route::middleware(['auth:admin', 'admin.menu'])->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    });

});
