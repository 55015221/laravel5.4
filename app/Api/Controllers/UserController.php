<?php

namespace App\Api\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class UserController extends BaseController
{

    /**
     * 用户列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $pageSize = $request->input('page_size', PAGE_SIZE);

        $uid = $request->input('uid');
        $username = $request->input('username');
        $email = $request->input('email');
        $dateStart = $request->input('dateStart');
        $dateEnd = $request->input('dateEnd');

        /**
         * 查询字段
         */
        $columns = [
            '*',
        ];

        /* @var $paginate LengthAwarePaginator */
        $paginate = User::when($uid, function (Builder $query) use ($uid) {
            $query->where('id', $uid);
        })->when($username, function (Builder $query) use ($username) {
            $query->where('username', 'like', "%{$username}%");
        })->when($email, function (Builder $query) use ($email) {
            $query->where('email', 'like', "%{$email}%");
        })->when($dateStart, function (Builder $query) use ($dateStart) {
            $query->where('created_at', '>=', $dateStart);
        })->when($dateEnd, function (Builder $query) use ($dateEnd) {
            $query->where('created_at', '<=', $dateEnd);
        })
            ->orderBy('id', 'desc')
            ->paginate($pageSize, $columns);

        $accessRecords = $paginate->getCollection();

        $accessRecords->each(function (User $user) {
            //格式化数据
            $user->statusText = User::$userStatus[$user->status];

        });

        return $this->responseData($paginate);
    }

    /**
     * 获取用户信息
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = User::find($id);

        return $this->responseData($user);
    }

    /**
     * 获取用户信息 （只能是自己的信息）
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function current(Request $request)
    {
        $user = $this->user();
        if (!$user) {
            return $this->responseError(ERROR_UNKNOWN, '用户不存在');
        }
        list($validate, $allValidations) = $user->ability(
            Role::pluck('name')->toArray(),
            Permission::pluck('name')->toArray(),
            [
                'validate_all' => true,
                'return_type'  => 'both'
            ]
        );
        return $this->responseData(compact('user', 'allValidations'));
    }

    /**
     * 测试
     */
    public function test()
    {
        /* @var $user User */
//        $user = User::where('username', 'admin')->first();
        //用户拥有的角色
//        dd($user->roles);
//        给用户分配角色
//        dd($user->detachRole(1));
//        删除角色的权限
//        dd($user->attachRole(1));

//        $user->roles()->attach(1);


        $role = Role::where('name', 'admin')->first();
//        某个角色拥有的权限
//        dd($role->perms);

//        分配权限给角色
        dd($role->attachPermissions([17, 18, 19]));
//        批量分配权限给角色（注意：是重新分配，之前的都会去掉 不是追加）
//        dd($role->perms()->sync([5,6,7,8,9,10,11,12]));
//        删掉角色中的权限
//        dd($role->detachPermission(1));

//        dd($role->users()->sync([])); // 删除关联数据
//        dd($role->perms()->sync([])); // 删除关联数据


//        判断用户是否属于某个用户组:
//        $user->hasRole("Owner");    // false
//        $user->hasRole("Admin");    // true
//        判断用户是否拥有某个权限(通过用户组):
//        $user->can("manage_posts"); // true
//        $user->can("manage_users"); // false


    }

}