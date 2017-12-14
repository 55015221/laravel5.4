<?php
/**
 * 分配权限请求类
 *
 * @author Bily
 * @date 2017-8-7 11:40:20
 */

namespace App\Api\Requests;


class PermissionAssignRequest extends ApiRequest
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
            'roleId'         => 'required|integer',
            'permissionId'   => 'required|array',
            'permissionId.*' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'roleId.required'        => '角色ID必须填写',
            'roleId.integer'         => '角色ID必须是整型',
            'permissionId.required'  => '权限ID必须填写',
            'permissionId.array'     => '权限ID必须是数组',
            'permissionId.*.integer' => '权限ID必须是整型',
        ];
    }
}
