@extends('layout')

@section('content')

<h2 class="bg-danger text-white p-3">{{ $exception->getMessage() ?: 'Page not found' }}</h2>

@endsection

