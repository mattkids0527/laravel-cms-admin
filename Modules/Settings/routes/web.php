<?php

use Illuminate\Support\Facades\Route;
use Modules\Settings\App\Livewire\AppearanceSettings;

Route::prefix('admin')->name('admin.')->middleware(['web', 'auth:admin', 'admin.menu'])->group(function () {

    Route::get('settings/appearance', AppearanceSettings::class)->name('settings.appearance');

});
