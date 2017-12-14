<?php
/**
 * 分配角色请求类
 *
 * @author Bily
 * @date 2017-8-7 11:40:20
 */

namespace App\Api\Requests;


class RoleAssignRequest extends ApiRequest
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
            'uid'      => 'required|integer',
            'roleId'   => 'required|array',
            'roleId.*' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'uid.required'     => '用户ID必须填写',
            'uid.integer'      => '用户ID必须是整型',
            'roleId.required'  => '角色ID必须填写',
            'roleId.array'     => '角色ID必须是数组',
            'roleId.*.integer' => '角色ID必须是整型',
        ];
    }
}
