<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ isset($title) ? $title . ' - ' : '' }}@yield('db-title')</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" type="text/css" href="{{ mix('css/vendor.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ mix('css/app.css') }}">

    <script>
    window.default_locale = "{{ config('app.locale') }}";
    window.fallback_locale = "{{ config('app.fallback_locale') }}";
    </script>

    @yield('head')
</head>
<body class="d-flex flex-column h-100 {{ isset($base) ? $base->id : '' }}">

    <header>
        <div class="{{ App::environment() }}">
            This is the '{{ App::environment() }}' environment
        </div>
        <div class="uio-header">
            <div class="container-xl">
                <a href="https://www.uio.no/" title="Universitetet i Oslo"><img src="/images/uio-logo.svg" alt="Universitetet i Oslo"></a>
            </div>
        </div>
        <div class="header">
            <div class="header_top">
                <div class="container-xl">
                    <div>
                        <a href="/" >UB-baser</a>
                    </div>
                    <div id="user">

                        @if (isset($base) && (count($base->languages) > 1))
                            {{ __('messages.language') }}:
                            @foreach ($base->languages as $baseLang)
                                @if ($baseLang == \App::getLocale())
                                    {{ Str::ucfirst(\Punic\Language::getName($baseLang, $baseLang)) }}
                                @else
                                    <a href="?lang={{ $baseLang }}">{{ Str::ucfirst(\Punic\Language::getName($baseLang, $baseLang)) }}</a>
                                @endif
                            @endforeach
                            |
                        @endif

                        @if (Auth::check())

                            @can('admin')
                            <a href="{{ action('Admin\AdminController@index') }}">{{ trans('messages.admin') }}</a> |
                            @endcan

                            <a href="{{ action('LogEntryController@index') }}">{{ trans('messages.logs') }}</a> |

                            {!! trans('messages.loggedinas', [
                                'user' => '<a href="' . action('AccountController@index') . '" id="user_name">' . Auth::user()->name . '</a>'
                            ]) !!}

                            | <a href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>

                        @else

                            <span id="user_name"></span><!-- Empty for tests -->
                            <a href="{{ URL::current() }}?login=true">{{ trans('messages.login') }}</a>

                        @endif

                    </div>
                </div>
            </div>
            <div class="header_bottom">
                <div class="container-xl">
                    <h1>
                        @hasSection('header')
                            @yield('header')
                        @elseif (isset($base))
                            <a href="{{ $base->action('index') }}">{{ $base->title }}</a>
                        @else
                            <a href="/">UB-baser</a>
                        @endif
                    </h1>
                </div>
            </div>
        </div>
        @yield('header_after')
    </header>

    <main id="app" class="flex-shrink-0">

        <div class="container-xl">
            @if ((new \Jenssegers\Agent\Agent() )->browser() === 'IE')

                <div class="card bg-danger text-white mb-3">
                  <div class="card-body">
                    Du bruker Internet Explorer, en utdatert nettleser som UB-baser dessverre ikke støtter særlig bra.
                    Vi anbefaler å bytte til en mer moderne nettleser om du kan, som Edge, Firefox eller Chrome.
                  </div>
                </div>

            @endif

            @if (session('status'))
                <div class="alert alert-info">
                    {{ session('status') }}
                </div>
            @endif

            @yield('content')
        </div>

    </main>

    <footer>
        <div class="container-xl text-center text-md-left align-items-center">

            <!-- Column -->
            <div class="logo" ></div>

            <!-- Column -->
            <div>
                <h5>@yield('db-title')</h5>
                @hasSection('footer-column1')
                    @yield('footer-column1')
                @else
                    <ul class="list-unstyled">
                        <li>
                            UB-baser er en tjeneste fra <a href="https://www.ub.uio.no/">Universitetsbiblioteket i Oslo</a>.
                            <br>&nbsp;
                        </li>
                    </ul>
                @endif
            </div>

            <!-- Column -->
            <div class="ft-col-3">
                <h5 class="d-none d-md-block">&nbsp;</h5>
                @hasSection('footer-column2')
                    @yield('footer-column2')
                @else
                    <ul class="list-unstyled">
                        <li>
                            @lang('messages.responsible'):<br>
                            <a href="mailto:ub-web@ub.uio.no" title="Nettredaktør">ub-web@ub.uio.no</a>
                        </li>
                    </ul>
                @endif
            </div>

        </div>
    </footer>

    <script src="{{ mix('js/app.js') }}"></script>
    @yield('script')
</body>
</html>
