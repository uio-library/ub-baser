@extends('layout')

@section('content')

    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ action('Admin\AdminController@index') }}">{{ trans('messages.admin') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ trans('messages.managebases') }}</li>
        </ol>
    </nav>

    <h2>{{ trans('messages.managebases') }}</h2>

    <ul>
        @foreach ($bases as $base)
            <li><a href="{{ action('Admin\BaseController@show', $base->id) }}">{{ $base->title }}</a></li>
        @endforeach
    </ul>

@endsection
