<?php

/**
 * 系统操作日志 路由中间件
 * 记录系统的操作日志
 *
 * @author Bily
 * @date 2017-8-7 11:40:20
 */

namespace App\Api\Middleware;

use Agent;
use App\Jobs\AccessRecordJob;
use Auth;
use Carbon\Carbon;
use Closure;


class AccessRecordRequests
{

    protected $guard = 'user';


    /**
     * @param $request
     * @param Closure $next
     * @param int $delay
     * @return mixed
     */
    public function handle($request, Closure $next, $delay = 10)
    {
        $response = $next($request);

        return $response;
    }

    /**
     * Perform any final actions for the request lifecycle.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Symfony\Component\HttpFoundation\Response $response
     * @return void
     */
    public function terminate($request, $response)
    {
        try {
            $path = $request->path();
            $method = $request->method();
            $action = $request->route()->getAction();

            /**
             * 组合入库数据
             * @var $user User
             */
            $user = Auth::guard($this->guard)->user();

            $content = (string)$response->content();
            $contentArray = json_decode($content, true);

            $params = [
                'uid'      => isset($user->id) ? $user->id : 0,
                'url'      => $request->url(),
                'method'   => $method,
                'browser'  => Agent::browser(),
                'status'   => $response->getStatusCode(),
                'code'     => isset($contentArray['code']) ? $contentArray['code'] : 0,
                'ip'       => $request->ip(),
                'request'  => (string)json_encode($request->all()),
                'response' => $content,
            ];

            /**
             * 放入列队
             * 目前是同步执行 可以设置 每天 0点之后处理，减少对业务请求的影响
             */
            $job = (new AccessRecordJob($params))
//                ->onQueue('processing')
                ->delay(Carbon::now()->addSeconds(5));

            dispatch($job);
        } catch (\Exception $e) {
            logger('【AccessRecordRequest】', [
                $e->getMessage(), $e->getFile(), $e->getLine(),
            ]);
        }
    }
}
