<?php
/**
 * Request基类。所有Request类需继承此类
 *
 * @author Bily
 * @date 2017-8-7 11:40:20
 */

namespace App\Api\Requests;

use App\Api\Exceptions\ValidationFailedException;
use Dingo\Api\Http\FormRequest;
use App\Api\Exceptions\AuthorizedFailedException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Response;

abstract class ApiRequest extends FormRequest
{
    /**
     * 格式化验证失败响应
     */
    protected function failedAuthorization()
    {
        $response = Response::json(['code' => ERROR_AUTH_FAILED, 'message' => '认证失败']);
        throw new AuthorizedFailedException($response);
    }

    /**
     * 格式化验证失败响应
     *
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        $response = Response::json(['code' => ERROR_VALIDATION_FAILED, 'message' => '数据验证失败', 'errors' => $errors]);
        throw new ValidationFailedException($response);
    }
}