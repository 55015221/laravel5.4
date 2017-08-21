<?php
/**
 * 封装响应数据为统一格式的Trait
 *
 * 在需要的地方引用此Trait
 * 所有方法均返回一个\Illuminate\Http\JsonResponse实例
 */

namespace App\Api\Traits;

use Illuminate\Contracts\Support\Arrayable;
use Response;

/**
 * Class Responder
 * @package App\Api\V1\Traits
 */
trait Responder
{

    /**
     * 返回错误信息
     *
     * @param int $code 错误代码
     * @param string $message 错误信息
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseError($code, $message)
    {
        return $this->responseMessage($code, $message);
    }

    /**
     * 返回带数据的错误信息
     *
     * @param int $code 错误代码
     * @param string $message 错误信息
     * @param string|int|double|Arrayable $data 携带的数据
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseErrorWithData($code, $message, $data)
    {
        $data = ['code' => $code, 'message' => $message, 'data' => $data];
        return $this->createResponse($data);
    }

    /**
     * 返回成功信息
     *
     * @param string $message 要返回的文本信息
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseSuccess($message = '操作成功')
    {
        return $this->responseMessage(ERROR_OK, $message);
    }

    /**
     * 返回数据
     *
     * @param string|int|double|Arrayable $data 要返回的数据
     * @param string $message 要返回的提示信息
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseData($data, $message = '操作成功')
    {
        $data = ['code' => ERROR_OK, 'message' => $message, 'data' => $data];
        return $this->createResponse($data);
    }

    /**
     * 组装响应数据
     * @param int $code 错误代码
     * @param string $message 错误消息
     * @return \Illuminate\Http\JsonResponse
     */
    private function responseMessage($code, $message)
    {
        $data = ['code' => $code, 'message' => $message];
        return $this->createResponse($data);
    }

    /**
     * 生成响应数据
     *
     * @param array $data 响应数据
     * @return \Illuminate\Http\JsonResponse
     */
    private function createResponse($data)
    {
        //设置不缓存
        $headers = [
            'Cache-Control' => 'no-cache, no-store, max-age=0, must-revalidate',
            'Expires'       => -1,
            'Pragma'        => 'no-cache',
        ];

        return response()->json($data, 200, $headers);
    }
}