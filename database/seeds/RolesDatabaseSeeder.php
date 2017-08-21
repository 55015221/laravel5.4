<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesDatabaseSeeder extends Seeder
{
    public function run()
    {
        Model::unguard();

        DB::table('roles')->delete();

        $roles = [
            ['name' => 'owner', 'display_name' => '项目负责人', 'description' => '项目负责人只能管理自己的项目'],
            ['name' => 'admin', 'display_name' => '系统管理员', 'description' => '系统管理员可以管理其他的项目'],
        ];

        $user = User::where('username', '=', 'admin')->first();

        // Loop through each user above and create the record for them in the database
        foreach ($roles as $role) {
            $owner = new Role();
            $owner->name = $role['name'];
            $owner->display_name = $role['display_name']; // optional
            $owner->description = $role['description']; // optional
            $owner->save();

            // role attach alias
            $user->attachRole($owner); // parameter can be an Role object, array, or id
        }
    }
}