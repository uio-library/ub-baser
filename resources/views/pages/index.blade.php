@extends('layouts.master')

@section('content')

  <ol class="breadcrumb" style="margin-bottom: 5px;">
    <li><a href="{{ action('Admin\AdminController@index') }}">{{ trans('messages.admin') }}</a></li>
    <li class="active">{{ trans('messages.managepages') }}</li>
  </ol>

  <h2>{{ trans('messages.managepages') }}</h2>

  <ul>
    @foreach ($pages as $page)
      <li><a href="{{ route($page->name) }}">{{ $page->name }}</a></li>
    @endforeach
  </ul>

@endsection
