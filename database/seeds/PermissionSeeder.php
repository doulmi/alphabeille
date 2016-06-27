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

        Permission::create([
            'name' => '添加主题',
            'slug' => 'topic.add',
            'description' => '管理员是否能添加新主题'
        ]);

        Permission::create([
            'name' => '删除主题' ,
            'slug' => 'topic.delete',
            'description' => '管理员是否能删除主题'
        ]);

        Permission::create([
            'name' => '修改主题',
            'slug' => 'topic.modify',
            'description' => '管理员是否能修改主题'
        ]);

        Permission::create([
            'name' => '添加课程',
            'slug' => 'lesson.add',
            'description' => '管理员是否能添加课程'
        ]);

        Permission::create([
            'name' => '删除课程',
            'slug' => 'lesson.delete',
            'description' => '管理员是否能删除课程'
        ]);

        Permission::create([
            'name' => '修改课程',
            'slug' => 'lesson.modify',
            'description' => '管理员是否能添加课程'
        ]);
    }
}
