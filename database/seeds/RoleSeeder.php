<?php

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Bican\Roles\Models\Role::create([ 'name' => 'Admin', 'slug' => 'admin', 'description' => '管理员', 'level' => 4]);
        \Bican\Roles\Models\Role::create([ 'name' => 'ContentAdmin', 'slug' => 'contentAdmin', 'description' => '内容编辑管理员', 'level' => 3]);
        \Bican\Roles\Models\Role::create([ 'name' => 'Member', 'slug' => 'member', 'description' => '注册用户', 'level' => 1]);
        \Bican\Roles\Models\Role::create([ 'name' => 'VIP', 'slug' => 'VIP', 'description' => '付费用户', 'level' => 2]);
    }
}
