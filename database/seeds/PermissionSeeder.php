<?php

use Illuminate\Database\Seeder;
use Bican\Roles\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => '添加用户',
            'slug' => 'user.create',
            'description' => '管理员是否能添加用户',
            'model' => 'App\User',
        ]);

        Permission::create([
            'name' => '更改权限',
            'slug' => 'user.role',
            'description' => '管理员是否能更改其他用户的权限',
        ]);
    }
}
