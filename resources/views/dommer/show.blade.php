@extends('layouts.dommer')

@section('content')

<p>
	<a href="{{ URL::previous() }}"><i class="fa fa-arrow-circle-left"></i> Tilbake</a>
	@can('dommer')
		&nbsp;
		<a href="{{ action('DommerController@edit', $record->id) }}"><i class="fa fa-edit"></i> Rediger post</a>
	@endcan
</p>

<h2>
	«{{ $record->navn }}»
</h2>

<p>
	Referanse: {{ $record->kilde }} {{ $record->aar }}, side {{ $record->side }}
</p>

@endsection
