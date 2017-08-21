<?php

namespace App\Api\Controllers;

use Illuminate\Http\Request;

class UserController extends BaseController
{

    /**
     * 获取用户信息 （只能是自己的信息）
     * @param Request $request
     * @param $uid
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $uid)
    {
        if ($uid == $this->user()->id) {
            return $this->responseData($this->user());
        }
        return $this->responseError(1001, '用户不存在');
    }
}