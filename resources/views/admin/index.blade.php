@extends('layouts.master')

@section('content')

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page">{{ trans('messages.admin') }}</li>
    </ol>
</nav>

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
