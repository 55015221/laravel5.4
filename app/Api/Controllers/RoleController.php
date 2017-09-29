<?php
/**
 * 角色管理
 *
 * @author Bily
 * @date 2017-8-7 11:40:20
 */

namespace App\Api\Controllers;

use App\Models\Role;
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

        $name = $request->input('name');
        $displayName = $request->input('displayName');
        $description = $request->input('description');
        $dateStart = $request->input('dateStart');
        $dateEnd = $request->input('dateEnd');

        /* @var $paginate LengthAwarePaginator */
        $paginate = Role::when($name, function (Builder $query) use ($name) {
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

        $paginate->setCollection($roles);

        return $this->responseData($paginate);
    }

    /**
     * 查看角色
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        dd('查看文章');
    }

    public function create()
    {
        dd('新增文章');
    }

    public function update(Request $request, $id)
    {
        dd('修改文章');
    }

}