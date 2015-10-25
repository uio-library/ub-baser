@extends('layouts.master')

@section('content')

<ol class="breadcrumb" style="margin-bottom: 5px;">
  <li><a href="{{ action('Admin\AdminController@index') }}">{{ trans('messages.admin') }}</a></li>
  <li class="active">{{ trans('messages.manageusers') }}</li>
</ol>

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
