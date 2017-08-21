<?php
/**
 * 用户注册请求
 *
 * @author Bily
 * @date 2017-8-7 11:40:20
 */

namespace App\Api\Requests;


class LoginRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required|max:32|min:3|alpha_dash',
            'password' => 'required|min:6',
        ];
    }

    public function messages()
    {
        return [
            'username.required' => '用户名必须填写',
            'username.min'      => '用户名长度为3-32',
            'username.max'      => '用户名长度为3-32',
            'password.required' => '密码必须填写',
            'password.min'      => '密码最低6位',
        ];
    }
}
