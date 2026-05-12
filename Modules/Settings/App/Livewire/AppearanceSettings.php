<?php

namespace Modules\Settings\App\Livewire;

use Livewire\Component;

class AppearanceSettings extends Component
{
    public function render()
    {
        return view('settings::livewire.appearance-settings')
            ->layout('components.layouts.admin', ['title' => '外觀設定']);
    }
}
