<?php

namespace Database\Seeders;

use App\Models\AdminMenu;
use Illuminate\Database\Seeder;

class AdminMenuSeeder extends Seeder
{
    public function run(): void
    {
        // 儀表板（頂層項目，有路由）
        AdminMenu::firstOrCreate(
            ['route_name' => 'admin.dashboard'],
            [
                'parent_id'  => null,
                'name'       => '儀表板',
                'route_name' => 'admin.dashboard',
                'icon'       => 'home',
                'sort_order' => 1,
                'is_active'  => true,
            ]
        );

        // 系統管理（群組，無路由）
        $systemGroup = AdminMenu::firstOrCreate(
            ['name' => '系統管理', 'parent_id' => null, 'route_name' => null],
            [
                'parent_id'  => null,
                'name'       => '系統管理',
                'route_name' => null,
                'icon'       => 'cog',
                'sort_order' => 2,
                'is_active'  => true,
            ]
        );

        $systemItems = [
            [
                'name'       => '帳號管理',
                'route_name' => 'admin.users.index',
                'icon'       => 'users',
                'sort_order' => 1,
            ],
            [
                'name'       => '角色管理',
                'route_name' => 'admin.roles.index',
                'icon'       => 'shield',
                'sort_order' => 2,
            ],
            [
                'name'       => '選單權限',
                'route_name' => 'admin.menu-permissions.index',
                'icon'       => 'menu',
                'sort_order' => 3,
            ],
        ];

        foreach ($systemItems as $item) {
            AdminMenu::firstOrCreate(
                ['route_name' => $item['route_name']],
                array_merge($item, ['parent_id' => $systemGroup->id, 'is_active' => true])
            );
        }

        // 設定（群組，無路由）
        $settingsGroup = AdminMenu::firstOrCreate(
            ['name' => '設定', 'parent_id' => null, 'route_name' => null],
            [
                'parent_id'  => null,
                'name'       => '設定',
                'route_name' => null,
                'icon'       => 'settings',
                'sort_order' => 99,
                'is_active'  => true,
            ]
        );

        AdminMenu::firstOrCreate(
            ['route_name' => 'admin.settings.appearance'],
            [
                'parent_id'  => $settingsGroup->id,
                'name'       => '外觀設定',
                'route_name' => 'admin.settings.appearance',
                'icon'       => 'palette',
                'sort_order' => 1,
                'is_active'  => true,
            ]
        );
    }
}
