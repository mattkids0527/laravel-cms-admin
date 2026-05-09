<?php

namespace App\Livewire\Admin\Roles;

use App\Models\Role;
use Livewire\Component;

class RoleEdit extends Component
{
    public Role $role;

    public string $name = '';
    public string $description = '';

    public function mount(Role $role): void
    {
        $this->role        = $role;
        $this->name        = $role->name;
        $this->description = $role->description ?? '';
    }

    public function save(): void
    {
        if ($this->role->is_protected) {
            // 保護角色只允許修改描述
            $this->validate(['description' => 'nullable|string|max:255']);
            $this->role->update(['description' => $this->description ?: null]);
        } else {
            $this->validate([
                'name'        => "required|string|max:100|unique:roles,name,{$this->role->id}",
                'description' => 'nullable|string|max:255',
            ], [
                'name.required' => '請輸入角色名稱。',
                'name.unique'   => '此角色名稱已存在。',
            ]);
            $this->role->update([
                'name'        => $this->name,
                'description' => $this->description ?: null,
            ]);
        }

        session()->flash('success', '角色已更新成功。');
        $this->redirect(route('admin.roles.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.roles.edit')
            ->layout('components.layouts.admin', ['title' => '編輯角色']);
    }
}
