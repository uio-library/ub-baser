<!DOCTYPE html>
<html>
<head>

    <title>{{ isset($title) ? $title . ' - ' : '' }}@yield('db-title')</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="{{ mix('css/vendor.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ mix('css/app.css') }}">

    @yield('head')
</head>
<body>

    <div class="uio-header">
        <div class="container">
            <a href="https://www.uio.no/" title="Universitetet i Oslo"><img src="/images/uio-logo.svg" alt="Universitetet i Oslo"></a>
            <!-- Dette ser kanskje litt teit ut, men vi er pålagt å ha det her -->
        </div>
    </div>

    <header class="container">
        <div id="user">
            @if (Auth::check())
                {!! trans('messages.loggedinas', [
                    'user' => '<a href="' . action('AccountController@index') . '">' . Auth::user()->name . '</a>'
                ]) !!}.
                @can('admin')
                <a href="{{ action('Admin\AdminController@index') }}">{{ trans('messages.admin') }}</a>
                @endcan


                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>

            @else

                <a href="{{ URL::current() }}?login=true">{{ trans('messages.login') }}</a>

            @endif

        </div>

        <h1>
            <a href="/">UB-baser</a>
            @yield('header-part', '')
        </h1>

    </header>

    <div class="container" id="app">

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        @yield('content')
    </div>

    <script src="{{ mix('js/vendor.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>
    @if (Auth::check())
        <script src="{{ mix('js/editing.js') }}"></script>
    @endif

    @yield('script')
</body>
</html>