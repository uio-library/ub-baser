@extends('dommer.layout')

@section('content')

<p>
	<a href="{{ URL::previous() }}"><em class="fa fa-arrow-circle-left"></em> Tilbake</a>
	@can('dommer')
		&nbsp;
		<a href="{{ action('\App\Bases\Dommer\Controller@edit', $record->id) }}"><em class="fa fa-edit"></em> Rediger post</a>
	@endcan
</p>

<h2>
	«{{ $record->navn }}»
</h2>

<p>
	Referanse: {{ $record->kilde }} {{ $record->aar }}, side {{ $record->side }}
</p>

@endsection
