@extends('layout')

@section('content')
<div class="container">

    @if ($errors->any())
        <div class="alert alert-danger">
            <p>Innlogginga feilet:</p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row justify-content-center">

        <div class="col-md-8 accordion" id="accordionExample">

            <div class="card">
                <div class="card-header"><a href="#" onclick="showCard(1); return false;">{{ __('UiO-innlogging') }}</a></div>
                <div class="card-body collapse show" id="collapse1">

                    @if ($uioWebloginTenantUuid)
                        <a class="btn btn-primary" href="{{ action([Slides\Saml2\Http\Controllers\Saml2Controller::class, 'login'], ['uuid'=>$uioWebloginTenantUuid]) }}">
                            <em class="fa fa-arrow-right"></em>
                            Logg inn sikkert med Weblogin
                        </a>
                    @else
                        <em>Innlogging med Weblogin er ikke satt opp</em>
                    @endif

                </div>
            </div>

            <div class="card">
                <div class="card-header"><a id="collapse1_toggle" href="#" onclick="showCard(2); return false;">{{ __('Lokal innlogging (alternativ)') }}</a></div>

                <div class="card-body collapse" id="collapse2">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
function showCard(nr) {
    $('.collapse').removeClass('show');
    $('#collapse' + nr).addClass('show');
}

@if ($errors->has('email') || $errors->has('password'))
showCard(2);
@endif
</script>
@endsection
