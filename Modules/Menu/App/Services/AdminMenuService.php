<?php

namespace Modules\Menu\App\Services;

use Illuminate\Support\Collection;
use Modules\Account\App\Models\User;
use Modules\Menu\App\Models\AdminMenu;

class AdminMenuService
{
    public function getVisibleMenus(User $user): Collection
    {
        $groups = AdminMenu::with(['children' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')])
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        if ($user->isSuperAdmin()) {
            return $groups;
        }

        $allowedMenuIds = $user->roles()
            ->with('adminMenuPermissions')
            ->get()
            ->flatMap(fn ($role) => $role->adminMenuPermissions->pluck('id'))
            ->unique();

        return $groups->map(function ($group) use ($allowedMenuIds) {
            if ($group->isGroup()) {
                $group->setRelation(
                    'children',
                    $group->children->filter(fn ($item) => $allowedMenuIds->contains($item->id))->values()
                );
                return $group;
            }
            return $allowedMenuIds->contains($group->id) ? $group : null;
        })->filter(function ($group) {
            if (is_null($group)) return false;
            if ($group->isGroup()) return $group->children->isNotEmpty();
            return true;
        })->values();
    }

    public function canAccess(User $user, string $routeName): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        $menu = AdminMenu::where('route_name', $routeName)->where('is_active', true)->first();

        if (! $menu) {
            return true;
        }

        return $user->roles()
            ->whereHas('adminMenuPermissions', fn ($q) => $q->where('admin_menus.id', $menu->id))
            ->exists();
    }
}
