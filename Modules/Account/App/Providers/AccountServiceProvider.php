<?php

namespace Modules\Account\App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Modules\Account\App\Livewire\Roles\RoleCreate;
use Modules\Account\App\Livewire\Roles\RoleEdit;
use Modules\Account\App\Livewire\Roles\RoleIndex;
use Modules\Account\App\Livewire\Users\UserCreate;
use Modules\Account\App\Livewire\Users\UserEdit;
use Modules\Account\App\Livewire\Users\UserIndex;

class AccountServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'account');

        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');

        Livewire::component('account::users.user-index', UserIndex::class);
        Livewire::component('account::users.user-create', UserCreate::class);
        Livewire::component('account::users.user-edit', UserEdit::class);
        Livewire::component('account::roles.role-index', RoleIndex::class);
        Livewire::component('account::roles.role-create', RoleCreate::class);
        Livewire::component('account::roles.role-edit', RoleEdit::class);
    }
}
