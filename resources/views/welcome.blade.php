<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <title>Laravel</title>
    <meta name="keywords" content="Laravel学习网,Laravel5.5,Laravel,Lumen中文网,Php Artisan,Laravel教程,Laravel视频" />
    <meta name="description" content="Laravel学习网,主要用于学习php框架排行榜第一的laravel框架和lumen框架，包含laravel视频教程，laravel中文文档，laravel拓展包以及使用教程，致力于推动 Laravel，PHP7、php-fig，composer 等 PHP 新技术" />
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <link href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://cdn.bootcss.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::asset('css/main.css') }}">
    <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js"></script>

    <style>
        .links {
            text-align: center;
        }
        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
<div class="main-header">
    <div class="main-header-top-line">
        <a href="/">{{ config('app.name', 'Laravel') }}</a>
        <span class="fa fa-align-center" onclick="navigation();"></span>

        <ul>
            @if (Route::has('login'))
                @if (Auth::check())
                    <li><a href="{{ url('/home') }}">首页</a></li>
                @else
                    <li><a href="{{ url('/login') }}">登录</a></li>
                    <li><a href="{{ url('/register') }}">注册</a></li>
                @endif
            @endif
            <li><a href="">关于我们</a></li>
            <li><a href="">开源项目</a></li>
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
    <div class="title m-b-md" style="font-size: 84px;text-align: center;color: #636b6f;">
        Laravel
    </div>

    <div class="links">
        <a href="https://laravel.com/docs">Documentation</a>
        <a href="https://laracasts.com">Laracasts</a>
        <a href="https://laravel-news.com">News</a>
        <a href="https://forge.laravel.com">Forge</a>
        <a href="https://github.com/laravel/laravel">GitHub</a>
    </div>
</div>
<div class="main-footer">

</div>
</body>
</html>

