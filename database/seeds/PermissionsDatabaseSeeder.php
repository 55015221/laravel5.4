<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsDatabaseSeeder extends Seeder
{
    public function run()
    {
        Model::unguard();

        DB::table('permissions')->delete();

        $permissions = [
            ['name' => 'PostController@index', 'display_name' => '文章列表', 'description' => '查看文章列表'],
            ['name' => 'PostController@show', 'display_name' => '文章详情', 'description' => '查看文章详情'],
            ['name' => 'PostController@create', 'display_name' => '新增文章', 'description' => '新增文章'],
            ['name' => 'PostController@update', 'display_name' => '编辑文章', 'description' => '编辑文章'],
        ];

        /**
         * @var $role Role
         */
        $role = Role::where('name', '=', 'admin')->first();
        // Loop through each user above and create the record for them in the database
        foreach ($permissions as $permission) {
            $model = new Permission();
            $model->name = $permission['name'];
            $model->display_name = $permission['display_name']; // optional
            // Allow a user to...
            $model->description = $permission['description']; // optional
            $model->save();

            $role->attachPermission($model);
            // equivalent to $admin->perms()->sync(array($model->id));
        }
    }
}