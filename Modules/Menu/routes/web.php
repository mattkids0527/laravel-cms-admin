<?php

use Illuminate\Support\Facades\Route;
use Modules\Menu\App\Livewire\MenuPermissionIndex;

Route::prefix('admin')->name('admin.')->middleware(['web', 'auth:admin', 'admin.menu'])->group(function () {

    Route::get('menu-permissions', MenuPermissionIndex::class)->name('menu-permissions.index');

});
