@extends('layouts.master')

@section('content')

<ol class="breadcrumb" style="margin-bottom: 5px;">
  <li class="active">{{ trans('messages.admin') }}</li>
</ol>

<h2>{{ trans('messages.admin') }}</h2>

<ul>
    <li>
        <a href="{{ action('Admin\UserController@index') }}">{{ trans('messages.manageusers') }}</a>
    </li>
    <li>
        <a href="{{ action('PageController@index') }}">{{ trans('messages.managepages') }}</a>
    </li>
</ul>

@endsection
