<?php

namespace App\Api\Controllers;

use Illuminate\Http\Request;

class PostController extends BaseController
{

    public function index()
    {

        $user = $this->user();
        return $this->responseData($user);
        //当前用户 拥有的角色
        dd($user->roles()->allRelatedIds());
        dd($user);
    }

    /**
     * 查看文章
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