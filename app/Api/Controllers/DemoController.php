<?php
/**
 * 测试类
 *
 * @author Bily
 * @date 2017-8-7 11:40:20
 */

namespace App\Api\Controllers;


use Illuminate\Http\Request;

class DemoController extends BaseController
{

    public function index(Request $request)
    {
        $name = $request->input('name');

        $data = [
            'name' => $name,
        ];

        return $this->responseData($data);
    }

}