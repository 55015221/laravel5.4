<?php
/**
 * 角色管理
 *
 * @author Bily
 * @date 2017-8-7 11:40:20
 */

namespace App\Api\Controllers;

use App\Api\Requests\RoleAssignRequest;
use App\Api\Requests\RoleCreateRequest;
use App\Api\Requests\RoleUpdateRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends BaseController
{

    /**
     * 角色列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $this->user();

        $id = $request->input('id');
        $name = $request->input('name');
        $displayName = $request->input('displayName');
        $description = $request->input('description');
        $dateStart = $request->input('dateStart');
        $dateEnd = $request->input('dateEnd');

        /* @var $paginate \Illuminate\Pagination\LengthAwarePaginator */
        $paginate = Role::when($id, function (Builder $query) use ($id) {
            $query->where('id', $id);
        })->when($name, function (Builder $query) use ($name) {
            $query->where('name', 'like', "%{$name}%");
        })->when($displayName, function (Builder $query) use ($displayName) {
            $query->where('display_name', 'like', "%{$displayName}%");
        })->when($description, function (Builder $query) use ($description) {
            $query->where('description', 'like', "%{$description}%");
        })->when($dateStart, function (Builder $query) use ($dateStart) {
            $query->where('created_at', '>=', $dateStart);
        })->when($dateEnd, function (Builder $query) use ($dateEnd) {
            $query->where('created_at', '<=', $dateEnd);
        })
            ->paginate();
        $roles = $paginate->getCollection();

        $roles->each(function (Role &$role) {
            //格式化数据
            $role->displayName = $role->display_name;
            unset($role->display_name);
        });

        return $this->responseData($paginate);
    }

    /**
     * 查看角色
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        /* @var $role Role */
        $role = Role::find($id);

        if (!$role) {
            return $this->responseError(ERROR_UNKNOWN, '该角色不存在');
        }

        return $this->responseData($role);
    }

    /**
     * 某个角色拥有的权限
     * @param $roleId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function permissions($roleId, Request $request)
    {
        $name = $request->input('name');
        $displayName = $request->input('displayName');
        $description = $request->input('description');
//        @todo 不能分页，获取不了分页数据
//        $role = Role::with(['perms' => function ($query) use ($name, $displayName, $description,&$paginate) {
//            $paginate = $query->when($name, function (Builder $query) use ($name) {
//                $query->where('name', 'like', "%{$name}%");
//            })->when($displayName, function (Builder $query) use ($displayName) {
//                $query->where('display_name', 'like', "%{$displayName}%");
//            })->when($description, function (Builder $query) use ($description) {
//                $query->where('description', 'like', "%{$description}%");
//            })->paginate();
//        }])
//            ->find($roleId);

        $role = Role::find($roleId);
        $permissions = Permission::all();

        $hasPermissions = $role->perms->pluck('id');
        $permissions->map(function (&$permission) use ($hasPermissions) {
            $permission->displayName = $permission->display_name;
            $permission->authority = false;
            if ($hasPermissions->search($permission->id)) {
                $permission->authority = true;
            }
        });

        return $this->responseData($permissions);
    }

    /**
     * 分配权限给某个角色
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveRolePermission(Request $request)
    {
        try {
            $roleId = $request->get('id');
            $permissions = $request->get('permissions');

            //批量分配权限给角色（注意：是重新分配，之前的都会去掉 不是追加）
            $role = Role::find($roleId);

            dd($permissions);

            $role->perms()->sync([5, 6, 7, 8, 9, 10, 11, 12]);
            return $this->responseSuccess();
        } catch (Exception $e) {
            return $this->responseError(ERROR_UNKNOWN, $e->getMessage());
        }
    }

    /**
     * 创建角色
     * @param RoleCreateRequest $roleCreateRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(RoleCreateRequest $roleCreateRequest)
    {
        try {
            $data = $roleCreateRequest->only(['name', 'description']);
            $data['display_name'] = $roleCreateRequest->input('displayName');

            $role = new Role();
            $role->create($data);

            return $this->responseSuccess();
        } catch (Exception $e) {
            return $this->responseError(ERROR_UNKNOWN, $e->getMessage());
        }
    }

    /**
     * 修改角色
     * @param RoleUpdateRequest $roleUpdateRequest
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(RoleUpdateRequest $roleUpdateRequest, $id)
    {
        try {
            /* @var $role Role */
            $role = Role::find($id);
            if (!$role) {
                return $this->responseError(ERROR_UNKNOWN, '该角色不存在');
            }
            $data = $roleUpdateRequest->only(['name', 'description']);
            $data['display_name'] = $roleUpdateRequest->input('displayName');

            $role->update($data);

            return $this->responseSuccess();
        } catch (Exception $e) {
            return $this->responseError(ERROR_UNKNOWN, $e->getMessage());
        }
    }

    /**
     * 分角色给用户
     * @param integer $uid 用户ID
     * @param array $roleId 角色ID 数组
     * @param RoleAssignRequest $roleAssignRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function assign(RoleAssignRequest $roleAssignRequest)
    {
        try {
            $roleAssignRequest->only(['uid', 'roleId']);

            /* @var $user User */
            $user = User::find($roleAssignRequest->uid);
            if (!$user) {
                return $this->responseError(ERROR_UNKNOWN, '该用户不存在');
            }

            $user->attachRoles($roleAssignRequest->roleId);

            return $this->responseSuccess('保存成功');
        } catch (Exception $e) {
            return $this->responseError(ERROR_UNKNOWN, $e->getMessage());
        }
    }

}