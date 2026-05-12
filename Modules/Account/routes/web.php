<?php

use Illuminate\Support\Facades\Route;
use Modules\Account\App\Livewire\Roles\RoleCreate;
use Modules\Account\App\Livewire\Roles\RoleEdit;
use Modules\Account\App\Livewire\Roles\RoleIndex;
use Modules\Account\App\Livewire\Users\UserCreate;
use Modules\Account\App\Livewire\Users\UserEdit;
use Modules\Account\App\Livewire\Users\UserIndex;

Route::prefix('admin')->name('admin.')->middleware(['web', 'auth:admin', 'admin.menu'])->group(function () {

    Route::get('users', UserIndex::class)->name('users.index');
    Route::get('users/create', UserCreate::class)->name('users.create');
    Route::get('users/{user}/edit', UserEdit::class)->name('users.edit');

    Route::get('roles', RoleIndex::class)->name('roles.index');
    Route::get('roles/create', RoleCreate::class)->name('roles.create');
    Route::get('roles/{role}/edit', RoleEdit::class)->name('roles.edit');

});
