@extends('layouts.dommer')

@section('content')

		<h2>
			Opprett ny post
		</h2>

		@if ($errors->count())
		<p class="errors">
		    @foreach ($errors->all() as $error)
		        {{ $error }}
		    @endforeach
		</p>
		@endif

		<form method="POST" action="{{ action('DommerController@store') }}">
		    {!! csrf_field() !!}

			<table>
				@foreach ($columns as $column)
					<tr>
						<td>{{ $column['label'] }}</td>
						<td><input type="text"></td>
					</tr>
				@endforeach
			</table>

			<button type="submit">Lagre</button>
		</form>


@endsection
