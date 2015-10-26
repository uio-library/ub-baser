@extends('layouts.master')

@section('content')

<h2>{{ trans('messages.login') }}</h2>

@include('shared.errors')

<form method="POST" action="{{ action('Auth\AuthController@postLogin') }}">
    {!! csrf_field() !!}

    <div class="form-group row">
        <label for="emailInput" class="col-sm-2 form-control-label">{{ trans('messages.email') }}</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="emailInput" name="email" value="{{ old('email') }}">
        </div>
    </div>

    <div class="form-group row">
        <label for="passwordInput" class="col-sm-2 form-control-label">{{ trans('messages.password') }}</label>
        <div class="col-sm-10">
            <input type="password" class="form-control" id="passwordInput" name="password" value="{{ old('password') }}">
        </div>
    </div>

    <div>
        <label>
            <input type="checkbox" name="remember"> {{ trans('messages.rememberme') }}
        </label>
    </div>

    <div class="form-group row">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-primary">{{ trans('messages.login') }}</button>
        </div>
    </div>

</form>

<h3>Problemer med innlogging</h3>
<p>
    <a href="{{ action('Auth\PasswordController@getEmail') }}">{{ trans('messages.forgotpassword') }}</a>
</p>
<p>
    Ingen konto? Send en epost til xx@ub.uio.no
</p>


@endsection
