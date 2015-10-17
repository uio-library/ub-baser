<!DOCTYPE html>
<html>
<head>

    <title>{{ isset($title) ? $title . ' - ' : '' }}@yield('db-title')</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="{{ URL::to('css/app.css') }}">
    @yield('head')

</head>
<body>

    <header>
        <div id="user">
            @if (Auth::check())

                Innlogget som {{ Auth::user()->name }}.
                <a href="{{ action('Auth\AuthController@getLogout') }}">Logg ut</a>

            @else

                <a href="{{ action('Auth\AuthController@getLogin') }}">Logg på</a>

            @endif

        </div>

        <h1>@yield('db-title', 'UB-baser')</h1>

    </header>

    <div class="container">
        @yield('content')
    </div>

    <script src="{{ URL::to('js/app.js') }}"></script>
</body>
</html>