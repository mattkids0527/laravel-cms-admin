<?php

namespace Modules\Auth\App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Modules\Auth\App\Livewire\Login;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'auth');

        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');

        Livewire::component('auth::login', Login::class);
    }
}
