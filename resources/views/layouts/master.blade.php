<!DOCTYPE html>
<html>
<head>

    <title>{{ isset($title) ? $title . ' - ' : '' }}@yield('db-title')</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="{{ URL::to('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.1.2/css/material-design-iconic-font.min.css">
    @yield('head')

</head>
<body>

    <header>
        <div id="user">
            @if (Auth::check())
                {!! trans('messages.loggedinas', [
                    'user' => '<a href="' . action('AccountController@index') . '">' . Auth::user()->name . '</a>'
                ]) !!}.
                @can('admin')
                <a href="{{ action('Admin\AdminController@index') }}">{{ trans('messages.admin') }}</a>
                @endcan
                <a href="{{ action('Auth\AuthController@getLogout') }}">{{ trans('messages.logout') }}</a>

            @else

                <a href="{{ URL::current() }}?login=true">{{ trans('messages.login') }}</a>

            @endif

        </div>

        <h1><a href="@yield('db-url', '/')">@yield('db-title', 'UB-baser')</a></h1>

    </header>

    <div class="container">

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        @yield('content')
    </div>

    <script src="{{ URL::to('js/vendor.js') }}"></script>
    <script src="{{ URL::to('js/app.js') }}"></script>
</body>
</html>