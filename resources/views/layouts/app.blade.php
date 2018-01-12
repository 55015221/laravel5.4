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
    <link rel="stylesheet" href="{{ URL::asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/main.css') }}">
</head>
<body>

@include('layouts.header')

<main class="site-main">
    @yield('content')
</main>

@include('layouts.footer')

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>