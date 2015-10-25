@extends('layouts.dommer')

@section('content')

<tt>
	show.blade.php
</tt>

<h2>
	#{{ $record->id }}
</h2>

<p>
	Navn: {{ $record->navn }}
	Ref: {{ $record->kilde }}, {{ $record->side }}
</p>

@endsection
