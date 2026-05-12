<?php

namespace Modules\Settings\App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Modules\Settings\App\Livewire\AppearanceSettings;

class SettingsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'settings');

        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');

        Livewire::component('settings::appearance-settings', AppearanceSettings::class);
    }
}
