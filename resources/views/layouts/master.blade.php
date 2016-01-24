<!DOCTYPE html>
<html>
<head>

    <title>{{ isset($title) ? $title . ' - ' : '' }}@yield('db-title')</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/table-view/css/themes/tableview-a.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ elixir('css/vendor.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ elixir('css/app.css') }}">
    @yield('head')

</head>
<body>

    <header class="container">
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

        <h1>
            <a href="/">UB-baser</a>
            @yield('header-part', '')
        </h1>

    </header>

    <div class="container">

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        @yield('content')
    </div>

    <script src="{{ elixir('js/vendor.js') }}"></script>
    @if (Auth::check())
        <script src="{{ elixir('js/editing.js') }}"></script>
    @endif

    @yield('script')
</body>
</html>