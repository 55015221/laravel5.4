<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="keywords" content="Laravel学习网,Laravel5.5,Laravel,Lumen中文网,Php Artisan,Laravel教程,Laravel视频" />
    <meta name="description" content="Laravel学习网,主要用于学习php框架排行榜第一的laravel框架和lumen框架，包含laravel视频教程，laravel中文文档，laravel拓展包以及使用教程，致力于推动 Laravel，PHP7、php-fig，composer 等 PHP 新技术" />
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <!-- Styles -->

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

<div class="main-header">
    <div class="main-header-top-line">
        <a href="https://phpartisan.cn/docs/5.5/">{{ config('app.name', 'Laravel') }}</a>
        <ul>
                @if (Auth::guest())
                    <li><a href="{{ route('login') }}">登录</a></li>
                    <li><a href="{{ route('register') }}">注册</a></li>
                @else
                    <li class="dropdown">
                        <a id="header-profile" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            {{ Auth::user()->username }} <i class="fa fa-caret-down"></i>
                        </a>

                        <ul class="dropdown-menu" role="menu" aria-labelledby="header-profile">
                            <li>
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Logout
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                @endif

            <li><a href="">关于我们</a></li>
            <li><a href="">开源项目</a></li>
            <li><a href="{{ url('/home') }}">首页</a></li>
        </ul>
    </div>
    <div class="main-header-content">
        <div class="main-header-content-header text-center">Laravel Artisan<small>Laravel学习网，为WEB艺术家创造的PHP框架。</small></div>
        <div class="main-header-content-form">
            <form action="/">
                <input type="text" placeholder="请输入你要搜索的内容,多关键字用空格隔开" name="keyword">
                <button class="fa fa-search"></button>
            </form>
        </div>

    </div>
</div>
<div class="main-navigation">

</div>
<div class="main-content">
    @yield('content')
</div>
<div class="main-footer">

</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
