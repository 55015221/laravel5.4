<?php

namespace App\Http\Controllers\Rights;

use App\Http\Controllers\Controller;
use App\Models\Permission;

/**
 * 权限集 控制器
 * @author Bily
 * @date 2018-02-05
 * Class PermissionController
 * @package App\Http\Controllers\Rights
 */
class PermissionController extends Controller
{

    public function __construct()
    {

    }

    public function index()
    {
        $permissions = Permission::get();
//        $permissions->each();
    }

    public function create()
    {

    }

    public function update()
    {

    }
}
