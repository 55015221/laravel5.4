<?php
/**
 * 登录状态检查中间件
 *
 * @author Bily
 * @date 2017-8-7 11:40:20
 */

namespace App\Api\Middleware;

use App\Api\Traits\Responder;
use Closure;
use Dingo\Api\Routing\Helpers;
use Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class CheckAuthenticate
{
    use Helpers, Responder;

    /**
     * @var string 验证Guard配置名，在config/auth.php中配置
     */
    protected $guard = 'user';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            /**
             * 验证登录
             */
            if (!Auth::guard($this->guard)->check()) {
                return $this->responseError(ERROR_NOT_LOGIN, '尚未登录或登录状态已超时');
            }

            /**
             * 验证授权
             */
            $actionName = $request->route()->getActionName();
            if (!$request->user($this->guard)->can($actionName)) {
                return $this->responseError(ERROR_NO_PERMIT, '无权限访问');
            }

        } catch (TokenExpiredException $e) {
            return $this->responseError(ERROR_TOKEN_EXPIRE, '令牌已过期');
        } catch (TokenInvalidException $e) {
            return $this->responseError(ERROR_TOKEN_INVALID, '令牌无效');
        } catch (JWTException $e) {
            return $this->responseError(ERROR_TOKEN_BAD, '令牌错误');
        }


        return $next($request);
    }
}
