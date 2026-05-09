<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('admin.login'));

Route::prefix('admin')->name('admin.')->group(function () {

    // 未登入可存取
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', \App\Livewire\Admin\Auth\Login::class)->name('login');
    });

    // 登入後才可存取
    Route::middleware(['auth:admin', 'admin.menu'])->group(function () {
        Route::post('logout', \App\Http\Controllers\Admin\AuthController::class . '@logout')->name('logout');
        Route::get('dashboard', \App\Livewire\Admin\Dashboard::class)->name('dashboard');

        // 帳號管理
        Route::get('users', \App\Livewire\Admin\Users\UserIndex::class)->name('users.index');
        Route::get('users/create', \App\Livewire\Admin\Users\UserCreate::class)->name('users.create');
        Route::get('users/{user}/edit', \App\Livewire\Admin\Users\UserEdit::class)->name('users.edit');

        // 角色管理
        Route::get('roles', \App\Livewire\Admin\Roles\RoleIndex::class)->name('roles.index');
        Route::get('roles/create', \App\Livewire\Admin\Roles\RoleCreate::class)->name('roles.create');
        Route::get('roles/{role}/edit', \App\Livewire\Admin\Roles\RoleEdit::class)->name('roles.edit');

        // 選單權限管理
        Route::get('menu-permissions', \App\Livewire\Admin\MenuPermissions\MenuPermissionIndex::class)->name('menu-permissions.index');
    });

});
