<?php

namespace App\Api\Controllers;

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

//        DB::connection()->enableQueryLog();
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

//        dd(DB::getQueryLog());
        $accessRecords = $paginate->getCollection();

        $accessRecords->each(function (User $user) {
            //格式化数据
            $user->statusText = User::$userStatus[$user->status];

        });

        return $this->responseData($paginate);
    }

    /**
     * 获取用户信息 （只能是自己的信息）
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        $user = $this->user();
        if ($user) {
            return $this->responseData($user);
        }
        return $this->responseError(1001, '用户不存在');
    }

}