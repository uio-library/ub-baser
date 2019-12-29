@extends('layout')

@section('content')

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ action('Admin\AdminController@index') }}">{{ trans('messages.admin') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ action('Admin\UserController@index') }}">{{ trans('messages.manageusers') }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ trans('messages.createuser') }}</li>
    </ol>
</nav>

<h2>{{ trans('messages.createuser') }}</h2>

@include('shared.errors')

<form method="POST" action="{{ action('Admin\UserController@store') }}">
    @csrf

    <div class="form-group row">
        <label for="nameInput" class="col-sm-2 form-control-label">{{ trans('messages.name') }}</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="nameInput" name="name" value="{{ old('name') }}">
        </div>
    </div>

    <div class="form-group row">
        <label for="emailInput" class="col-sm-2 form-control-label">{{ trans('messages.email') }}</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="emailInput" name="email" value="{{ old('email') }}">
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2">{{ trans('messages.rights') }}</label>
        <div class="col-sm-10">
            <div class="checkbox">
                @foreach ($rights as $right => $label)
                <div>
                    <label>
                        {!! Form::checkbox('right-' . $right) !!}
                        {{ $label }}
                    </label>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-primary">{{ trans('messages.create') }}</button>
        </div>
    </div>

</form>

@endsection
