<?php
/**
 * 节流器中间件
 *
 * @author Bily
 * @date 2017-8-7 11:40:20
 */

namespace App\Api\Middleware;


use App\Api\Traits\Responder;
use Illuminate\Routing\Middleware\ThrottleRequests;

class ApiThrottleRequests extends ThrottleRequests
{
    use Responder;

    /**
     * Create a 'too many attempts' response.
     *
     * @param  string $key
     * @param  int $maxAttempts
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function buildResponse($key, $maxAttempts)
    {
        $response = $this->responseError(ERROR_TOO_MANY_ATTEMPTS, '访问过于频繁，请稍后再试');

        $retryAfter = $this->limiter->availableIn($key);

        return $this->addHeaders(
            $response, $maxAttempts,
            $this->calculateRemainingAttempts($key, $maxAttempts, $retryAfter),
            $retryAfter
        );
    }
}