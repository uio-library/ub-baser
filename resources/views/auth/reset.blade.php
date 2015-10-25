@extends('layouts.master')

@section('content')

<h2>{{ trans('messages.setpassword') }}</h2>

@include('shared.errors')

<form method="POST" action="/password/reset">
    {!! csrf_field() !!}
    <input type="hidden" name="token" value="{{ $token }}">

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

    <div class="form-group row">
        <label for="passwordConfirmationInput" class="col-sm-2 form-control-label">{{ trans('messages.confirmpassword') }}</label>
        <div class="col-sm-10">
            <input type="password" class="form-control" id="passwordConfirmationInput" name="password_confirmation" value="{{ old('password') }}">
        </div>
    </div>

    <div class="form-group row">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-primary">{{ trans('messages.setpassword') }}</button>
        </div>
    </div>

</form>

@endsection
