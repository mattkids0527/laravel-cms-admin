<?php

namespace Modules\Dashboard\App\Livewire;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('dashboard::livewire.dashboard')
            ->layout('components.layouts.admin', ['title' => '儀表板']);
    }
}
