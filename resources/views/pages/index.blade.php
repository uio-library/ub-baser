@extends('layout')

@section('content')

  <nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ action('Admin\AdminController@index') }}">{{ trans('messages.admin') }}</a></li>
      <li class="breadcrumb-item active" aria-current="page">{{ trans('messages.managepages') }}</li>
    </ol>
  </nav>

  <h2>{{ trans('messages.managepages') }}</h2>

  <ul>
    @foreach ($pages as $page)
      <li><a href="/{{ $page->slug }}">{{ $page->slug }}</a></li>
    @endforeach
  </ul>

@endsection
