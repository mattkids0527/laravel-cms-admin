<?php

namespace Modules\Menu\App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Modules\Menu\App\Livewire\MenuPermissionIndex;
use Modules\Menu\App\Services\AdminMenuService;

class MenuServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(AdminMenuService::class);
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'menu');

        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');

        Livewire::component('menu::menu-permission-index', MenuPermissionIndex::class);
    }
}
