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
            $api->get('/users/{uid}', 'UserController@show');
            //注销当前登录用户
            $api->get('/logout', 'AuthController@logout');


            $api->get('/posts', 'PostController@index');
            $api->post('/posts', 'PostController@create');
            $api->get('/posts/{id}', 'PostController@show');
            $api->patch('/posts/{id}', 'PostController@update');
        });
    });

});
