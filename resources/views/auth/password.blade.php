@extends('layouts.master')

@section('content')

<h2>{{ trans('messages.resetpassword') }}</h2>

@include('shared.errors')

<form method="POST" action="/password/email">
    {!! csrf_field() !!}

    <div class="form-group row">
        <label for="emailInput" class="col-sm-2 form-control-label">{{ trans('messages.email') }}</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="emailInput" name="email" value="{{ old('email') }}">
        </div>
    </div>

    <div class="form-group row">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-primary">{{ trans('messages.sendpasswordresetlink') }}</button>
        </div>
    </div>

</form>

@endsection
