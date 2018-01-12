<header class="site-header" id="header">
    <nav class="navbar">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#navbar-collapse" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="{{ url('/') }}" class="navbar-brand">{{ config('app.name','Laravel') }}</a>
            </div>

            <!-- .navbar-collapse -->
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="{{ url('/') }}">首页</a></li>
                    <li><a href="">关于我们</a></li>
                    <li><a href="">开源项目</a></li>

                    @if (Auth::guest())
                        <li><a href="{{ route('login') }}">登录</a></li>
                        <li><a href="{{ route('register') }}">注册</a></li>
                    @else
                        <li class="dropdown">
                            <a id="profile" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-haspopup="true" aria-expanded="false">
                                {{ Auth::user()->username }} <i class="fa fa-caret-down"></i>
                            </a>

                            <ul class="dropdown-menu" role="menu" aria-labelledby="profile">
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
            </div>
            <!-- .navbar-collapse -->

        </div>
    </nav>
</header>