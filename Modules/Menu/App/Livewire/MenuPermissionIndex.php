<?php

namespace Modules\Menu\App\Livewire;

use Livewire\Component;
use Modules\Account\App\Models\Role;
use Modules\Menu\App\Models\AdminMenu;

class MenuPermissionIndex extends Component
{
    public ?int $selectedRoleId = null;
    public array $selectedMenuIds = [];

    public function selectRole(int $roleId): void
    {
        $this->selectedRoleId = $roleId;

        $role = Role::findOrFail($roleId);
        $this->selectedMenuIds = $role->adminMenuPermissions()
            ->pluck('admin_menus.id')
            ->map(fn ($id) => (string) $id)
            ->toArray();
    }

    public function save(): void
    {
        if (! $this->selectedRoleId) {
            return;
        }

        $role = Role::findOrFail($this->selectedRoleId);

        $validIds = AdminMenu::whereIn('id', $this->selectedMenuIds)
            ->whereNotNull('route_name')
            ->pluck('id');

        $role->adminMenuPermissions()->sync($validIds);

        session()->flash('success', "「{$role->name}」的選單權限已儲存。");
    }

    public function render()
    {
        $roles = Role::orderBy('id')->get();

        $groups = AdminMenu::with('children')
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('menu::livewire.menu-permissions.index', compact('roles', 'groups'))
            ->layout('components.layouts.admin', ['title' => '選單權限']);
    }
}
