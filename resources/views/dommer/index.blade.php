@extends('layouts.dommer')

@section('content')

<h2>
	index.blade.php
</h2>

@if (Auth::check())
	<p>
		<a href="{{ action('DommerController@create') }}">Opprett ny post</a>
	</p>
@endif

<table>
<tr>
	@foreach ($columns as $column)
	<th>
		<a href="{{ $column['link'] }}">{{ $column['label'] }}</a>
	</th>
	@endforeach
</tr>
@foreach ($records as $record)
	<tr>
		@foreach ($columns as $column)
		<td>
			{{ $record->{$column['field']} }}
		</td>
		@endforeach
	</tr>
@endforeach

</table>

{!! $records->appends(['sort' => $sortColumn, 'order' => $sortOrder])->render() !!}


@endsection
