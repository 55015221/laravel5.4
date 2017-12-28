<?php
/**
 * 用户服务
 * 用于封装常用的逻辑功能
 * @author Bily
 * @date 2017-12-18
 */

namespace App\Services;

use App\Models\User;

class UserService
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

}