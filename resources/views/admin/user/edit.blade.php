@extends('layouts.master')

@section('content')

<ol class="breadcrumb" style="margin-bottom: 5px;">
  <li><a href="{{ action('Admin\AdminController@index') }}">{{ trans('messages.admin') }}</a></li>
  <li><a href="{{ action('Admin\UserController@index') }}">{{ trans('messages.manageusers') }}</a></li>
  <li class="active">{{ $user->name }}</li>
</ol>

<h2>{{ $user->name }}</h2>

@include('shared.errors')

<div class="panel panel-default">

    <div class="panel-heading">
        <h3 class="panel-title">Rediger bruker</h3>
    </div>

    <div class="panel-body">

        <form method="POST" action="{{ action('Admin\UserController@update', $user->id) }}">
            {!! csrf_field() !!}

            <div class="form-group row">
                <label for="nameInput" class="col-sm-2 form-control-label">{{ trans('messages.name') }}</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="nameInput" name="name" value="{{ old('name') ?: $user->name }}">
                </div>
            </div>

            <div class="form-group row">
                <label for="emailInput" class="col-sm-2 form-control-label">{{ trans('messages.email') }}</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="emailInput" name="email" value="{{ old('email') ?: $user->email }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2">{{ trans('messages.rights') }}</label>
                <div class="col-sm-10">
                    <div class="checkbox">
                        @foreach ($rights as $right)
                        <div>
                            <label>
                                {!! Form::checkbox('right-' . $right, 'on', old('right-' . $right) ?: $user->can($right)) !!}
                                {{ trans('rights.' . $right) }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-primary">{{ trans('messages.save') }}</button>
                </div>
            </div>

        </form>

    </div>
</div>

<div class="panel panel-default">

    <div class="panel-heading">
        <h3 class="panel-title">Slett bruker</h3>
    </div>

    <div class="panel-body">

        <form method="POST" action="{{ action('Admin\UserController@destroy', $user->id) }}">
            {!! csrf_field() !!}

            <div class="form-group row">
                <div class="col-sm-12">
                    <label>
                        <input type="checkbox" name="confirm-deleteuser">
                        {{ trans('messages.confirm-deleteuser') }}
                    </label>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-danger">{{ trans('messages.deleteuser') }}</button>
                </div>
            </div>

        </form>

    </div>
</div>

@endsection
