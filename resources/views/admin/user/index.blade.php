@extends('layouts.master')

@section('content')

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ action('Admin\AdminController@index') }}">{{ trans('messages.admin') }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ trans('messages.manageusers') }}</li>
    </ol>
</nav>

<h2>{{ trans('messages.manageusers') }}</h2>

<p>
    <a href="{{ action('Admin\UserController@create') }}">{{ trans('messages.createuser') }}</a>
</p>

<ul>
    @foreach ($users as $user)
        <li>
            {{ $user->name }}
            (
                @foreach ($user->rights as $right)
                   {{ $right}}
                @endforeach
            )
            @can('admin')
                [<a href="{{ action('Admin\UserController@edit', $user->id) }}">{{ trans('messages.edit') }}</a>]
            @endcan
        </li>
    @endforeach
</ul>

@endsection
