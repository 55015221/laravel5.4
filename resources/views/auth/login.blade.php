<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Login for Laravel5.4</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="{{ asset('bootstrap-4.0.0/css/bootstrap.min.css') }}">

    <!-- Custom styles for this template -->
    <style>
        html,
        body {
            height: 100%;
        }

        body {
            display: -ms-flexbox;
            display: -webkit-box;
            display: flex;
            -ms-flex-align: center;
            -ms-flex-pack: center;
            -webkit-box-align: center;
            align-items: center;
            -webkit-box-pack: center;
            justify-content: center;
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #f5f5f5;
        }

        .form-signin {
            width: 100%;
            max-width: 540px;
        }

    </style>
</head>

<body class="text-center">
<div class="card bg-light form-signin">
    <div class="card-header">
        {{ config('app.name','Laravel') }} 管理系统
    </div>

    <div class="card-body">
        <form class="form-horizontal" method="POST" action="{{ route('login') }}">
            {{ csrf_field() }}

            <div class="row form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <label for="email" class="col-sm-4 col-form-label">E-Mail Address</label>

                <div class="col-sm-6">
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required
                           autofocus>

                    @if ($errors->has('email'))
                        <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
                    @endif
                </div>
            </div>

            <div class="row form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <label for="password" class="col-sm-4 col-form-label">Password</label>

                <div class="col-sm-6">
                    <input id="password" type="password" class="form-control" name="password" required>

                    @if ($errors->has('password'))
                        <span class="help-block"><strong>{{ $errors->first('password') }}</strong></span>
                    @endif
                </div>
            </div>

            <div class="row form-group">
                <div class="col-sm-6 offset-sm-3">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                        </label>
                    </div>
                    <a class="btn btn-link" href="{{ route('password.request') }}">
                        Forgot Your Password?
                    </a>
                </div>
            </div>

            <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
            <p class="mt-3 mb-1 text-muted">&copy; 2017-2018</p>
        </form>
    </div>
</div>
</body>
</html>