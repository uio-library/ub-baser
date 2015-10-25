@extends('layouts.master')

@section('content')

<h2>Logg inn</h2>

<p>
    <a href="{{ action('Auth\PasswordController@getEmail') }}">Glemt passord?</a>

    Ingen konto? Send en epost til xx@ub.uio.no
</p>

@include('shared.errors')

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
        <label>
            <input type="checkbox" name="remember"> Remember Me
        </label>
    </div>

    <div>
        <button type="submit">Login</button>
    </div>
</form>

@endsection
