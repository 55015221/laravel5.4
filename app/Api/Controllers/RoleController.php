<?php
/**
 * 角色管理
 *
 * @author Bily
 * @date 2017-8-7 11:40:20
 */

namespace App\Api\Controllers;

use App\Api\Requests\RoleCreateRequest;
use App\Api\Requests\RoleUpdateRequest;
use App\Models\Role;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

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

        /* @var $paginate LengthAwarePaginator */
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

}