<?php
/**
 * 控制器基类，所有控制器需继承此类
 *
 * @author Bily
 * @date 2017-8-7 11:40:20
 */

namespace App\Api\Controllers;

use App\Api\Traits\Responder;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BaseController extends Controller
{
    use Responder;

    protected $guard = 'user';

    /**
     * BaseController constructor.
     */
    public function __construct()
    {
        $this->middleware('throttle:60,1');     //一分钟最多尝试60次
        $this->middleware('access.api');     //访问记录 延时10秒
    }

    /**
     * 获取当前登录用户信息
     * @return User
     */
    protected function user()
    {
        return Auth::guard($this->guard)->user();
    }
}
