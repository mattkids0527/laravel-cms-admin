<?php

namespace App\Livewire\Admin\Roles;

use App\Models\Role;
use Livewire\Component;

class RoleCreate extends Component
{
    public string $name = '';
    public string $description = '';

    public function save(): void
    {
        $this->validate([
            'name'        => 'required|string|max:100|unique:roles,name',
            'description' => 'nullable|string|max:255',
        ], [
            'name.required' => '請輸入角色名稱。',
            'name.unique'   => '此角色名稱已存在。',
        ]);

        Role::create([
            'name'         => $this->name,
            'description'  => $this->description ?: null,
            'is_protected' => false,
        ]);

        session()->flash('success', '角色已建立成功。');
        $this->redirect(route('admin.roles.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.roles.create')
            ->layout('components.layouts.admin', ['title' => '新增角色']);
    }
}
