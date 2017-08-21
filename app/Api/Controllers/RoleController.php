<?php

namespace App\Api\Controllers;

use Illuminate\Http\Request;

class RoleController extends BaseController
{

    public function index(Request $request)
    {

        $user = $this->user();

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