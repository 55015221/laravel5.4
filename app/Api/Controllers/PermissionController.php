<?php
/**
 * 权限控制管理
 *
 * @author Bily
 * @date 2017-8-7 11:40:20
 */

namespace App\Api\Controllers;

use App\Api\Requests\PermissionCreateRequest;
use App\Api\Requests\PermissionUpdateRequest;
use App\Models\Permission;
use App\Models\Role;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

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

        $name = $request->input('name');
        $displayName = $request->input('displayName');
        $description = $request->input('description');
        $dateStart = $request->input('dateStart');
        $dateEnd = $request->input('dateEnd');

        /* @var $paginate LengthAwarePaginator */
        $paginate = Permission::when($name, function (Builder $query) use ($name) {
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
            $permission = $permissionCreateRequest->only(['name', 'description']);
            $permission['display_name'] = $permissionCreateRequest->input('displayName');

            Permission::save($permission);

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

            $permission->save($data);

            return $this->responseSuccess();
        } catch (Exception $e) {
            return $this->responseError(ERROR_UNKNOWN, $e->getMessage());
        }
    }

}