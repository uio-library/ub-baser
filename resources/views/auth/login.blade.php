@extends('layouts.master')

@section('content')

<p>
    Ingen konto?
    <a href="{{ action('Auth\AuthController@getRegister') }}">Registrer deg</a>
</p>

<form method="POST" action="{{ action('Auth\AuthController@postLogin') }}">
    {!! csrf_field() !!}

    <div>
        Email
        <input type="email" name="email" value="{{ old('email') }}">
    </div>

    <div>
        Password
        <input type="password" name="password" id="password">
    </div>

    <div>
        <input type="checkbox" name="remember"> Remember Me
    </div>

    <div>
        <button type="submit">Login</button>
    </div>
</form>

@endsection
