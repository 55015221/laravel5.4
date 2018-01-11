<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <title>Laravel</title>
    <meta name="keywords" content="Laravel学习网,Laravel5.5,Laravel,Lumen中文网,Php Artisan,Laravel教程,Laravel视频"/>
    <meta name="description"
          content="Laravel学习网,主要用于学习php框架排行榜第一的laravel框架和lumen框架，包含laravel视频教程，laravel中文文档，laravel拓展包以及使用教程，致力于推动 Laravel，PHP7、php-fig，composer 等 PHP 新技术"/>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <link href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::asset('css/main.css') }}">
    <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<header class="header">
    <nav class="navbar navbar-static-top main-navbar" id="top">
        <div class="container">
            <div class="navbar-header">
                <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#bs-navbar"
                        aria-controls="bs-navbar" aria-expanded="false"><span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span>
                </button>
                <a href="/" class="navbar-brand">{{ config('app.name', 'Laravel') }}</a></div>
            <nav class="collapse navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="{{ url('/home') }}">首页</a></li>
                    <li><a href="">关于我们</a></li>
                    <li><a href="">开源项目</a></li>

                    @if (Auth::guest())
                        <li><a href="{{ route('login') }}">登录</a></li>
                        <li><a href="{{ route('register') }}">注册</a></li>
                    @else
                        <li class="dropdown">
                            <a id="header-profile" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-haspopup="true" aria-expanded="false">
                                {{ Auth::user()->username }} <i class="fa fa-caret-down"></i>
                            </a>

                            <ul class="dropdown-menu" role="menu" aria-labelledby="header-profile">
                                <li>
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                          style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    </nav>
    <div class="container jumbotron">
        <div class="row">
            <div class="col-xs-12 text-center">
                <h1>Laravel Artisan</h1>
                <p>Laravel学习网，为WEB艺术家创造的PHP框架。</p>

                <div class="search-wraper" role="search">
                    <div class="form-group">
                        <input class="form-control search clearable" placeholder="请输入你要搜索的内容,多关键字用空格隔开">
                        <button class="fa fa-search"></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="main-content">
    <div class="container">
        @yield('content')
    </div>
</div>
<div class="footer">

</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>