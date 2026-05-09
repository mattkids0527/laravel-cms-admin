<?php

namespace App\Livewire\Admin\Settings;

use Livewire\Component;

class AppearanceSettings extends Component
{
    public function render()
    {
        return view('livewire.admin.settings.appearance-settings')
            ->layout('components.layouts.admin', ['title' => '外觀設定']);
    }
}
