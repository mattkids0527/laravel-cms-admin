<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('admin.login'));

Route::prefix('admin')->name('admin.')->group(function () {

    // 所有業務路由已移至各模組的 routes/web.php

});
