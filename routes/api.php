<?php

use Dingo\Api\Routing\Router;

$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function (Router $api) {


    /**
     * ------------------------------------------------------------------------------
     * 用户系统路由
     * ------------------------------------------------------------------------------
     *
     * 本组路由为用户系统路由
     */
    $api->group(['prefix' => 'api', 'namespace' => 'App\Api\Controllers'], function (Router $api) {


        /**
         * ------------------------------------------------------------------------------
         * 非认证路由
         * ------------------------------------------------------------------------------
         *
         * 本组路由为不需要认证即可访问的路由
         */
        $api->group([], function (Router $api) {

            //用户注册
            $api->post('register', 'AuthController@register');
            //用户登录
            $api->post('login', 'AuthController@login');
            //刷新token
            $api->get('refresh_token', 'AuthController@refreshToken');
            //登录状态检查（用于测试）
            $api->get('check', 'AuthController@check');
            //检查用户是否存在
            $api->get('check_user', 'AuthController@checkUserExists');
            //根据用户名或手机号查找用户基本信息，用于密码找回
            $api->get('check_user_info', 'AuthController@checkUserInfo');
            //检查短信验证码是否正确
            $api->get('check_sms_verify_code', 'AuthController@checkSmsVerifyCode');
            //重置密码
            $api->post('reset_password', 'AuthController@resetPassword');


            $api->get('test', 'UserController@test');

        });

        /**
         * ------------------------------------------------------------------------------
         * 需认证路由
         * ------------------------------------------------------------------------------
         *
         * 本组路由为需要认证才能访问的路由
         */
        $api->group(['middleware' => ['jwt.user.auth']], function (Router $api) {
            //获取用户的信息
            $api->get('/users', 'UserController@index');
            $api->get('/users/show', 'UserController@show');
            //注销当前登录用户
            $api->post('/logout', 'AuthController@logout');


            $api->get('/posts', 'PostController@index');
            $api->post('/posts', 'PostController@create');
            $api->get('/posts/{id}', 'PostController@show');
            $api->patch('/posts/{id}', 'PostController@update');

            //访问记录
            $api->get('/access_records', 'AccessRecordController@index');
            $api->get('/access_records/{id}', 'AccessRecordController@detail');

            /**
             * 角色管理
             */
            $api->get('/roles', 'RoleController@index'); //角色列表
            $api->post('/roles', 'RoleController@create'); //创建角色
            $api->patch('/roles/{id}', 'RoleController@update'); //修改角色
            $api->get('/roles/{id}', 'RoleController@show'); //查看角色

            /**
             * 权限控制管理
             */
            $api->get('/permissions', 'PermissionController@index'); //权限控制列表
            $api->post('/permissions', 'PermissionController@create'); //创建权限控制
            $api->patch('/permissions/{id}', 'PermissionController@update'); //修改权限控制
            $api->get('/permissions/{id}', 'PermissionController@show'); //查看权限控制

            /**
             * 图片
             */
            $api->get('/images', 'ImageController@index'); //图片列表

            $api->get('orders','OrderController@index');






        });
    });

});

