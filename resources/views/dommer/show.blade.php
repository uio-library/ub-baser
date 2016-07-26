@extends('layouts.dommer')

@section('content')

<tt>
	show.blade.php
	
</tt>

<h2>
	«{{ $record->navn }}»
</h2>

@can('dommer')
<p>
	<a href="{{ action('DommerController@edit', $record->id) }}">Rediger</a>
</p>
@endcan

<p>
	Referanse: {{ $record->kilde }} {{ $record->aar }}, side {{ $record->side }}
</p>

@endsection
