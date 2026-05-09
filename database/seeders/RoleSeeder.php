<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name'         => '超級管理員',
                'description'  => '可管理所有帳號、角色、系統設定與選單權限，不受任何功能限制',
                'is_protected' => true,
            ],
            [
                'name'         => '一般管理員',
                'description'  => '可操作被授權的功能模組，無法管理角色定義與系統層級設定',
                'is_protected' => false,
            ],
            [
                'name'         => '編輯人員',
                'description'  => '可新增、編輯被授權的內容，無法管理帳號或角色',
                'is_protected' => false,
            ],
            [
                'name'         => '檢視者',
                'description'  => '只能查看資料，不可執行新增、編輯、刪除等操作',
                'is_protected' => false,
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']], $role);
        }
    }
}
