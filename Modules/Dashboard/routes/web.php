<?php

use Illuminate\Support\Facades\Route;
use Modules\Dashboard\App\Livewire\Dashboard;

Route::prefix('admin')->name('admin.')->middleware(['web', 'auth:admin', 'admin.menu'])->group(function () {

    Route::get('dashboard', Dashboard::class)->name('dashboard');

});
