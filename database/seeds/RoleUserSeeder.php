<?php

use Illuminate\Database\Seeder;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = \App\User::all();

        $member = \Bican\Roles\Models\Role::where('name', 'Member')->first();
        $vip = \Bican\Roles\Models\Role::where('name', 'VIP')->first();

        $isVip = false;
        foreach($users as $user) {
            $user->attachRole($isVip ? $vip : $member);
            $isVip = !$isVip;
//            $user->save();
        }
    }
}
