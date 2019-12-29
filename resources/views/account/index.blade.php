@extends('layout')

@section('content')

		<h2>Min brukerkonto</h2>

		<p>
			Navn: {{ $user->name }}<br>
			Epost: {{ $user->email }}<br>
		</p>

@endsection
