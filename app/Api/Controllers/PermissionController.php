<?php
/**
 * 权限控制管理
 *
 * @author Bily
 * @date 2017-8-7 11:40:20
 */

namespace App\Api\Controllers;

use App\Api\Requests\PermissionAssignRequest;
use App\Api\Requests\PermissionCreateRequest;
use App\Api\Requests\PermissionUpdateRequest;
use App\Models\Permission;
use App\Models\Role;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use function Sodium\compare;

class PermissionController extends BaseController
{

    /**
     * 权限控制列表
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
        $paginate = Permission::when($id, function (Builder $query) use ($id) {
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
        $permissions = $paginate->getCollection();

        $permissions->each(function (Permission &$permission) {
            //格式化数据
            $permission->displayName = $permission->display_name;
            unset($permission->display_name);
        });

        return $this->responseData($paginate);
    }

    /**
     * 查看权限控制
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        /* @var $permission Permission */
        $permission = Permission::find($id);

        if (!$permission) {
            return $this->responseError(ERROR_UNKNOWN, '该权限控制不存在');
        }

        return $this->responseData($permission);
    }

    /**
     * 创建权限控制
     * @param PermissionCreateRequest $permissionCreateRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(PermissionCreateRequest $permissionCreateRequest)
    {
        try {
            $data = $permissionCreateRequest->only(['name', 'description']);
            $data['display_name'] = $permissionCreateRequest->input('displayName');

            $permission = new Permission();
            $permission->create($data);

            return $this->responseSuccess();
        } catch (Exception $e) {
            return $this->responseError(ERROR_UNKNOWN, $e->getMessage());
        }
    }

    /**
     * 修改权限控制
     * @param PermissionUpdateRequest $permissionUpdateRequest
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(PermissionUpdateRequest $permissionUpdateRequest, $id)
    {
        try {
            /* @var $permission Permission */
            $permission = Permission::find($id);

            if (!$permission) {
                return $this->responseError(ERROR_UNKNOWN, '该权限控制不存在');
            }

            $data = $permissionUpdateRequest->only(['name', 'description']);
            $data['display_name'] = $permissionUpdateRequest->input('displayName');

            $permission->update($data);

            return $this->responseSuccess();
        } catch (Exception $e) {
            return $this->responseError(ERROR_UNKNOWN, $e->getMessage());
        }
    }

    /**
     * 分配权限给角色
     * @param integer $roleId 角色ID
     * @param array $permissionId 权限ID 数组
     * @param PermissionAssignRequest $permissionAssignRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function assign(PermissionAssignRequest $permissionAssignRequest)
    {
        try {
            $permissionAssignRequest->only(['permission', 'roleId']);

            /* @var $role Role */
            $role = Role::find($permissionAssignRequest->roleId);
            if (!$role) {
                return $this->responseError(ERROR_UNKNOWN, '该角色不存在');
            }

            $permissionIds = array_column($permissionAssignRequest->permission, 'id');

            //批量分配权限给角色（注意：是重新分配，之前的都会去掉 不是追加）
            $role->perms()->sync($permissionIds);
            //追加分配权限
            //$role->attachPermissions();

            return $this->responseSuccess('保存成功');
        } catch (Exception $e) {
            return $this->responseError(ERROR_UNKNOWN, $e->getMessage());
        }
    }

}