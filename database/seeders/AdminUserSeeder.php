<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

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
