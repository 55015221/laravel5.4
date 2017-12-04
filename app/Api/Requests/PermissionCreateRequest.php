<?php
/**
 * 创建权限请求类
 *
 * @author Bily
 * @date 2017-8-7 11:40:20
 */

namespace App\Api\Requests;


class PermissionCreateRequest extends ApiRequest
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
            'name'        => 'required|max:32|min:3',
            'displayName' => 'required|min:3',
            'description' => 'min:3',
        ];
    }

    public function messages()
    {
        return [
            'name.required'        => '权限控制名称必须填写',
            'name.min'             => '权限控制名称长度为3-32',
            'name.max'             => '权限控制名称长度为3-32',
            'displayName.required' => '显示名称必须填写',
            'displayName.min'      => '显示名称最低6位',
            'description.min'      => '描述说明最低6位',
        ];
    }
}
