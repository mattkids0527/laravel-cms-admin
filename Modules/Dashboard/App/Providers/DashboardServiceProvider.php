<?php

namespace Modules\Dashboard\App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Modules\Dashboard\App\Livewire\Dashboard;

class DashboardServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'dashboard');

        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');

        Livewire::component('dashboard::dashboard', Dashboard::class);
    }
}
