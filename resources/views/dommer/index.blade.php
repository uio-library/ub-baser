@extends('layouts.dommer')

@section('content')

<tt>index.blade.php</tt>

<h2>
	Oversikt
</h2>

@if (Auth::check())
	<p>
		<a href="{{ action('DommerController@create') }}">Opprett ny post</a>
	</p>
@endif


@include('shared.sortable-table')


@endsection
