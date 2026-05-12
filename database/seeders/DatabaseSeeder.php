<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Account\Database\Seeders\AdminUserSeeder;
use Modules\Account\Database\Seeders\RoleSeeder;
use Modules\Menu\Database\Seeders\AdminMenuSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            AdminUserSeeder::class,
            AdminMenuSeeder::class,
        ]);
    }
}
