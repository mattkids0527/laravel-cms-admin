<?php

namespace Modules\Account\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Account\App\Models\Role;
use Modules\Account\App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $superRole = Role::where('is_protected', true)->firstOrFail();

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'     => '系統管理員',
                'password' => bcrypt('password'),
                'status'   => User::STATUS_ACTIVE,
            ]
        );

        if (! $admin->roles()->where('id', $superRole->id)->exists()) {
            $admin->roles()->attach($superRole->id);
        }
    }
}
