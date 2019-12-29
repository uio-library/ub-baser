@extends('layout')

@section('content')

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ action('Admin\AdminController@index') }}">{{ trans('messages.admin') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ action('Admin\UserController@index') }}">{{ trans('messages.manageusers') }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $user->name }}</li>
    </ol>
</nav>

@include('shared.errors')

<div class="card my-3">

    <div class="card-header">
        Rediger bruker
    </div>

    <div class="card-body">

        <form method="POST" action="{{ action('Admin\UserController@update', $user->id) }}">
            {!! csrf_field() !!}

            <div class="form-group row">
                <label for="nameInput" class="col-sm-2 col-form-label">{{ trans('messages.name') }}</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="nameInput" name="name" value="{{ old('name') ?: $user->name }}">
                </div>
            </div>

            <div class="form-group row">
                <label for="emailInput" class="col-sm-2 col-form-label">{{ trans('messages.email') }}</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="emailInput" name="email" value="{{ old('email') ?: $user->email }}">
                </div>
            </div>

            <div class="form-group row">
                <label for="saml_idInput" class="col-sm-2 col-form-label">{{ trans('messages.saml_id') }}</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="saml_idInput" name="saml_id" value="{{ old('saml_id') ?: $user->saml_id }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2">{{ trans('messages.rights') }}</label>
                <div class="col-sm-10">
                    <div class="checkbox">
                        @foreach ($rights as $right => $label)
                        <div>
                            <label>
                                {!! Form::checkbox('right-' . $right, 'on', old('right-' . $right) ?: $user->can($right)) !!}
                                {{ $label }}
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

<div class="card border-danger my-3">

    <div class="card-header">
        Slett bruker
    </div>

    <div class="card-body">

        <form method="POST" action="{{ action('Admin\UserController@destroy', $user->id) }}">
            @csrf

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
