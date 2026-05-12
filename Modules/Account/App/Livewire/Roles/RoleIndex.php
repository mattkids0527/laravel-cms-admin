<?php

namespace Modules\Account\App\Livewire\Roles;

use Livewire\Component;
use Modules\Account\App\Models\Role;

class RoleIndex extends Component
{
    public ?int $confirmingDeleteId = null;

    public function confirmDelete(int $roleId): void
    {
        $this->confirmingDeleteId = $roleId;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDeleteId = null;
    }

    public function delete(int $roleId): void
    {
        $role = Role::withCount('users')->findOrFail($roleId);

        if ($role->is_protected) {
            session()->flash('error', '系統保護角色不可刪除。');
            $this->confirmingDeleteId = null;
            return;
        }

        if ($role->users_count > 0) {
            session()->flash('error', '此角色已指派給 ' . $role->users_count . ' 個帳號，請先解除帳號關聯後再刪除。');
            $this->confirmingDeleteId = null;
            return;
        }

        $role->delete();
        $this->confirmingDeleteId = null;
        session()->flash('success', '角色已刪除。');
    }

    public function render()
    {
        $roles = Role::withCount('users')->orderBy('id')->get();

        return view('account::livewire.roles.index', compact('roles'))
            ->layout('components.layouts.admin', ['title' => '角色管理']);
    }
}
